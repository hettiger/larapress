<?php namespace Larapress\Commands;

use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Illuminate\Console\Command;
use Cartalyst\Sentry\Sentry;
use Larapress\Services\Mockably;
use UnexpectedValueException;

class InstallCommand extends Command {

	protected $email = 'admin@example.com';
	protected $password = 'password';
	protected $url;

	protected $groups = array(
		'administrator' => array
		(
			'name'        => 'Administrator',
			'permissions' => array(
				'access.backend'       => 1,
				'administrator.edit'   => 1,
				'owner.add'            => 1,
				'owner.remove'         => 1,
				'owner.edit'           => 1,
				'moderator.add'        => 1,
				'moderator.remove'     => 1,
				'moderator.edit'       => 1,
				'self.remove'          => 0,
				'content.administrate' => 1,
				'content.manage'       => 1,
				'configuration.edit'   => 1,
				'partial.add'          => 1,
				'partial.edit'         => 1,
			),
		),
		'owner' => array
		(
			'name'        => 'Owner',
			'permissions' => array(
				'access.backend'       => 1,
				'administrator.edit'   => 0,
				'owner.add'            => 0,
				'owner.remove'         => 0,
				'owner.edit'           => 1,
				'moderator.add'        => 1,
				'moderator.remove'     => 1,
				'moderator.edit'       => 1,
				'self.remove'          => 0,
				'content.administrate' => 0,
				'content.manage'       => 1,
				'configuration.edit'   => 1,
				'partial.add'          => 0,
				'partial.edit'         => 1,
			),
		),
		'moderator' => array
		(
			'name'        => 'Moderator',
			'permissions' => array(
				'access.backend'       => 1,
				'administrator.edit'   => 0,
				'owner.add'            => 0,
				'owner.remove'         => 0,
				'owner.edit'           => 0,
				'moderator.add'        => 0,
				'moderator.remove'     => 0,
				'moderator.edit'       => 0,
				'self.remove'          => 1,
				'content.administrate' => 0,
				'content.manage'       => 1,
				'configuration.edit'   => 0,
				'partial.add'          => 0,
				'partial.edit'         => 0,
			),
		)
	);

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'larapress:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install larapress.';

	/**
	 * @var \Cartalyst\Sentry\Sentry
	 */
	private $sentry;

	/**
	 * @var \Larapress\Services\Mockably
	 */
	private $mockably;

	/**
	 * Create a new command instance.
	 *
	 * @param \Cartalyst\Sentry\Sentry $sentry
	 * @param \Larapress\Services\Mockably $mockably
	 * @return InstallCommand
	 */
	public function __construct(Sentry $sentry, Mockably $mockably)
	{
		parent::__construct();

		$this->sentry = $sentry;
		$this->mockably = $mockably;
		$this->url = $mockably->route('larapress.home.login.get');
	}

	/**
	 * Abort command execution with a given error message
	 *
	 * @param string $message
	 */
	protected function abort_command($message)
	{
		$this->call('migrate:reset');
		$this->error($message);
		$this->mockably->mockable_die();
	}

	/**
	 * Create User Groups / Roles
	 *
	 * @return GroupInterface Returns the Administrator group
	 */
	public function create_user_groups()
	{
		$admin_group = null;

		try
		{
			$admin_group = $this->sentry->createGroup($this->groups['administrator']);
			$this->sentry->createGroup($this->groups['owner']);
			$this->sentry->createGroup($this->groups['moderator']);
		}
		catch (NameRequiredException $e)
		{
			$this->abort_command('Name field is required');
		}
		catch (GroupExistsException $e)
		{
			$this->abort_command('Group already exists');
		}

		return $admin_group;
	}

	/**
	 * Provide more specific error messages on unexpected value exceptions
	 *
	 * @param UnexpectedValueException $e
	 */
	protected function handle_unexpected_value_exception($e)
	{
		switch (get_class($e))
		{
			case 'Cartalyst\Sentry\Users\UserExistsException':
				$this->abort_command('User with this login already exists.');
				break;
			case 'Cartalyst\Sentry\Groups\GroupNotFoundException':
				$this->abort_command('Group was not found.');
				break;
		}

		$this->abort_command('Unexpected value error.');
	}

	/**
	 * Add the admin user
	 *
	 * @param GroupInterface $admin_group The Administrator group
	 * @return void
	 */
	public function add_the_admin_user($admin_group)
	{
		try
		{
			$user = $this->sentry->createUser(
				array(
					'email'     => $this->email,
					'password'  => $this->password,
					'activated' => true,
				)
			);

			$user->addGroup($admin_group);
		}
		catch (UnexpectedValueException $e)
		{
			$this->handle_unexpected_value_exception($e);
		}
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Installing larapress ...' . PHP_EOL);

		$this->call('migrate', array('--package' => 'cartalyst/sentry'));
		$admin_group = $this->create_user_groups();

		if ($admin_group instanceof GroupInterface)
		{
			$this->add_the_admin_user($admin_group);
		}
		else
		{
			$this->abort_command('Failed creating the user groups.');
		}

		$this->info(PHP_EOL . 'Installation complete!' . PHP_EOL);
		$this->info('Now please visit ' . $this->url . ' and login.' . PHP_EOL);
		$this->info('Credentials:');
		$this->info('E-Mail: ' . $this->email);
		$this->info('Password: ' . $this->password . PHP_EOL);
		$this->info('Make sure you instantly update your credentials!');
	}

}
