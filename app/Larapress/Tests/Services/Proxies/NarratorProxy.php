<?php namespace Larapress\Tests\Services\Proxies;

use Larapress\Services\Narrator;
use Mockery\Mock;

class NarratorProxy extends Narrator {

	public function getCmsName()
	{
		return $this->cmsName;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getFrom()
	{
		return $this->from;
	}

	public function getMailErrorMessage()
	{
		return $this->mailErrorMessage;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getTo()
	{
		return $this->to;
	}

	public function getView()
	{
		return $this->view;
	}

	public function init()
	{
		parent::init();
	}

	public function handleTransportExceptions($e)
	{
		parent::handleTransportExceptions($e);
	}

	/**
	 * @param array $input
	 * @param Mock $user
	 * @param string $reset_code
	 */
	public function prepareResetRequestMailData($input, $user, $reset_code)
	{
		parent::prepareResetRequestMailData($input, $user, $reset_code);
	}

	/**
	 * @param Mock $user
	 * @param string $reset_code
	 * @return string
	 */
	public function unsuspendUserAndResetPassword($user, $reset_code)
	{
		return parent::unsuspendUserAndResetPassword($user, $reset_code);
	}

	/**
	 * @param Mock $user
	 * @param string $reset_code
	 * @return string
	 */
	public function attemptToReset($user, $reset_code)
	{
		return parent::attemptToReset($user, $reset_code);
	}

	/**
	 * @param Mock $user
	 * @param string $reset_code
	 */
	public function prepareResetResultMailData($user, $reset_code)
	{
		parent::prepareResetResultMailData($user, $reset_code);
	}

}
