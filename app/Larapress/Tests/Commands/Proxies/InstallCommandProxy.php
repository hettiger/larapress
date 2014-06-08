<?php namespace Larapress\Tests\Commands\Proxies;

use Larapress\Commands\InstallCommand;

class InstallCommandProxy extends InstallCommand {

	/**
	 * @param string $message
	 */
	public function abort_command($message)
	{
		parent::abort_command($message);
	}

	/**
	 * Save the command into the $_SERVER variable so we can test what has been called
	 *
	 * @param string $command
	 * @param array $arguments
	 * @return void
	 */
	public function call($command, array $arguments = array())
	{
		$_SERVER['call'][] = $command;
	}

	/**
	 * Save the message into the $_SERVER variable so we can test what has been called
	 *
	 * @param string $message
	 * @return void
	 */
	public function error($message)
	{
		$_SERVER['error'][] = $message;
	}

	/**
	 * Save the message into the $_SERVER variable so we can test what has been called
	 *
	 * @param string $message
	 * @return void
	 */
	public function info($message)
	{
		$_SERVER['info'][] = $message;
	}

	public function handle_unexpected_value_exception($e)
	{
		parent::handle_unexpected_value_exception($e);
	}

	/**
	 * @param string $admin_group A string is fine for testing ...
	 * @return void
	 */
	public function add_the_admin_user($admin_group)
	{
		parent::add_the_admin_user($admin_group);
	}

}
