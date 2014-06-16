<?php namespace Larapress\Services;

use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Users\UserInterface as User;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request as Input;
use Illuminate\Mail\Mailer as Mail;
use Illuminate\Translation\Translator as Lang;
use Larapress\Exceptions\MailException;
use Larapress\Exceptions\PasswordResetCodeInvalidException;
use Larapress\Exceptions\PasswordResetFailedException;
use Larapress\Interfaces\MockablyInterface;
use Larapress\Interfaces\NarratorInterface;
use Larapress\Interfaces\NullObjectInterface;
use Swift_TransportException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class Narrator implements NarratorInterface {

	protected $cmsName;
	protected $view;
	protected $to;
	protected $from;
	protected $subject;
	protected $data;
	protected $mailErrorMessage;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	/**
	 * @var \Illuminate\Mail\Mailer
	 */
	private $mail;

	/**
	 * @var \Illuminate\Translation\Translator
	 */
	private $lang;

	/**
	 * @var \Illuminate\Http\Request
	 */
	private $input;

	/**
	 * @var \Cartalyst\Sentry\Sentry
	 */
	private $sentry;

	/**
	 * @var NullObject
	 */
	private $nullObject;

	/**
	 * @var Mockably
	 */
	private $mockably;

	/**
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Mail\Mailer $mail
	 * @param \Illuminate\Translation\Translator $lang
	 * @param \Illuminate\Http\Request $input
	 * @param \Cartalyst\Sentry\Sentry $sentry
	 * @param \Larapress\Interfaces\NullObjectInterface $nullObject
	 * @param \Larapress\Interfaces\MockablyInterface $mockably
	 *
	 * @return Narrator
	 */
	public function __construct(
		Config $config,
		Mail $mail,
		Lang $lang,
		Input $input,
		Sentry $sentry,
		NullObjectInterface $nullObject,
		MockablyInterface $mockably
	) {
		$this->config = $config;
		$this->mail = $mail;
		$this->lang = $lang;
		$this->input = $input;
		$this->sentry = $sentry;
		$this->nullObject = $nullObject;
		$this->mockably = $mockably;

		$this->init();
	}

	protected function init()
	{
		$from = array(
			'address' => $this->config->get('larapress.email.from.address'),
			'name'    => $this->config->get('larapress.email.from.name'),
		);

		$this->setFrom($from);
		$this->setCmsName($cms_name = $this->config->get('larapress.names.cms'));
	}

	/**
	 * Set the cms name
	 *
	 * @param string $cmsName
	 */
	protected function setCmsName($cmsName)
	{
		$this->cmsName = $cmsName;
	}

	/**
	 * Data for the view/-s of your email
	 *
	 * @param array $data The data you want to pass to the view
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function setData($data)
	{
		$this->nullObject->validateVarIsArray($data);

		$this->data = $data;
	}

	/**
	 * The addressor for the email to send
	 *
	 * @param array $from From details: 'address' and 'name' (Provide strings)
	 * @return void
	 */
	public function setFrom($from)
	{
		$this->nullObject->validateMailRecipientData($from);

		$this->from = $from;
	}

	/**
	 * This will be the exception message if sending fails
	 *
	 * @param string $mailErrorMessage The error message
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function setMailErrorMessage($mailErrorMessage)
	{
		$this->nullObject->validateVarIsString($mailErrorMessage);

		$this->mailErrorMessage = $mailErrorMessage;
	}

	/**
	 * The email subject
	 *
	 * @param string $subject The translated email subject
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function setSubject($subject)
	{
		$this->nullObject->validateVarIsString($subject);

		$this->subject = $subject;
	}

	/**
	 * The destination address for your mail
	 *
	 * @param array $to To details: 'address' and 'name' (Provide strings)
	 * @return void
	 */
	public function setTo($to)
	{
		$this->nullObject->validateMailRecipientData($to);

		$this->to = $to;
	}

	/**
	 * The view/-s for your email
	 *
	 * @param array|string $view The view you want to use (Further information can be found in the laravel docs)
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function setView($view)
	{
		$this->nullObject->validateMailViewDetails($view);

		$this->view = $view;
	}

	/**
	 * Handle transport exception
	 *
	 * @param Swift_TransportException $e
	 * @throws MailException Throws a friendly exception message
	 */
	protected function handleTransportExceptions($e)
	{
		switch ($e->getMessage())
		{
			case 'Cannot send message without a sender address':
				throw new MailException($e->getMessage());
			default:
				throw new MailException($this->mailErrorMessage);
		}
	}

	/**
	 * Send a simple email
	 *
	 * For more complex emails you might write another method
	 *
	 * @throws MailException Throws an exception containing further information as message
	 * @return void
	 */
	public function sendMail()
	{
		try
		{
			$this->mail->send($this->view, $this->data, // @codeCoverageIgnoreStart
				function ($message)
				{
					$message->from($this->from['address'], $this->from['name']);
					$message->to($this->to['address'], $this->to['name'])->subject($this->subject);
				} // @codeCoverageIgnoreEnd
			);

			if ( $this->mail->failures() != array() )
			{
				throw new MailException($this->mailErrorMessage);
			}
		}
		catch (Swift_TransportException $e)
		{
			$this->handleTransportExceptions($e);
		}
	}

	/**
	 * Prepare an email for account reset requests
	 *
	 * @param User $user
	 * @param string $reset_code
	 */
	protected function prepareResetRequestMailData($user, $reset_code)
	{
		$to = array(
			'address' => $user->getAttribute('email'),
			'name'    => $user->getAttribute('first_name') . ' ' . $user->getAttribute('last_name')
		);

		$data = array(
			'cms_name' => $this->cmsName,
			'url'      => $this->mockably->route('larapress.home.send.new.password.get', array($user->getId(), $reset_code)),
		);

		$this->setTo($to);
		$this->setSubject($this->cmsName . ' | ' . $this->lang->get('larapress::email.Password Reset!'));
		$this->setData($data);
		$this->setView(array('text' => 'larapress::emails.reset-password'));
	}

	/**
	 * Request an account reset
	 *
	 * This will generate a reset password code for the given user and send it via email to him.
	 *
	 * @param Input|null $input Passing Input::all() can be omitted
	 * @throws UserNotFoundException Throws a UserNotFoundException if Sentry cannot find the given user.
	 * @throws MailException Throws an exception containing further information as message
	 * @return void
	 */
	public function resetPassword($input = null)
	{
		$input = $input ? : $this->input->all();
		$user = $this->sentry->findUserByLogin($input['email']);
		$reset_code = $user->getResetPasswordCode();

		$this->prepareResetRequestMailData($user, $reset_code);
		$this->setMailErrorMessage('Sending the email containing the reset key failed. ' .
			'Please try again later or contact the administrator.');

		$this->sendMail();
	}

	/**
	 * Unsuspend the user and give him a new password
	 *
	 * @param User $user
	 * @param string $reset_code The password reset code
	 * @throws PasswordResetFailedException
	 * @return string Returns the new password on success
	 */
	protected function unsuspendUserAndResetPassword($user, $reset_code)
	{
		$throttle = $this->sentry->findThrottlerByUserId($user->getId());
		$throttle->unsuspend();

		$new_password = $this->mockably->str_random(16);

		if ( $user->attemptResetPassword($reset_code, $new_password) )
		{
			return $new_password;
		}
		else
		{
			throw new PasswordResetFailedException;
		}
	}

	/**
	 * Attempt to reset a user
	 *
	 * Initiate the account reset process
	 *
	 * @param User $user
	 * @param string $reset_code The password reset code
	 * @throws PasswordResetFailedException Throws an exception without further information on failure
	 * @throws PasswordResetCodeInvalidException Throws an exception without further information on failure
	 * @return string Returns the new password on success
	 */
	protected function attemptToReset($user, $reset_code)
	{
		if ( $user->checkResetPasswordCode($reset_code) )
		{
			return $this->unsuspendUserAndResetPassword($user, $reset_code);
		}
		else
		{
			throw new PasswordResetCodeInvalidException;
		}
	}

	/**
	 * Prepare an email for account reset results
	 *
	 * @param User $user
	 * @param string $reset_code
	 * @throws PasswordResetCodeInvalidException
	 * @throws PasswordResetFailedException
	 */
	protected function prepareResetResultMailData($user, $reset_code)
	{
		$to = array(
			'address' => $user->getAttribute('email'),
			'name'    => $user->getAttribute('first_name') . ' ' . $user->getAttribute('last_name'),
		);

		$this->setTo($to);
		$this->setSubject($this->cmsName . ' | ' . $this->lang->get('larapress::email.Password Reset!'));
		$this->setData(array('new_password' => $this->attemptToReset($user, $reset_code)));
		$this->setView(array('text' => 'larapress::emails.new-password'));
	}

	/**
	 * Attempt to reset a user and send him a new password
	 *
	 * This will unsuspend a user and give him a new password.
	 *
	 * @param int $id The user id
	 * @param string $reset_code The password reset code
	 * @throws PasswordResetFailedException Throws an exception without further information on failure
	 * @throws PasswordResetCodeInvalidException Throws an exception without further information on failure
	 * @throws MailException Throws an exception containing further information as message
	 * @throws UserNotFoundException Throws an exception without further information on failure
	 * @return void
	 */
	public function sendNewPassword($id, $reset_code)
	{
		$user = $this->sentry->findUserById($id);

		$this->prepareResetResultMailData($user, $reset_code);
		$this->setMailErrorMessage('Sending the email containing the new password failed. ' .
			'Please try again later or contact the administrator.');

		$this->sendMail();
	}

}
