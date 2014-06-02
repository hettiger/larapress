<?php namespace Larapress\Tests\Commands\Proxies;

use Cartalyst\Sentry\Groups\GroupInterface;

class GroupProxy implements GroupInterface {

	/**
	 * Returns the group's ID.
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return null;
	}

	/**
	 * Returns the group's name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return null;
	}

	/**
	 * Returns permissions for the group.
	 *
	 * @return array
	 */
	public function getPermissions()
	{
		return null;
	}

	/**
	 * Saves the group.
	 *
	 * @return bool
	 */
	public function save()
	{
		return null;
	}

	/**
	 * Delete the group.
	 *
	 * @return bool
	 */
	public function delete()
	{
		return null;
	}

}
