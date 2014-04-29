<?php namespace Larapress\Tests\Commands;

use Artisan;
use Larapress\Tests\TestCase;
use Sentry;

class InstallCommandTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        Artisan::call('larapress:install');
    }

    /**
     * Running the larapress installer consumes quite a bit of time
     * Therefore we should run the setUp as seldom as possible
     */
    public function test_if_user_and_groups_exist()
    {
        $this->admin_user_exists();
        $this->administrator_group_exists();
        $this->owner_group_exists();
        $this->moderator_group_exists();
    }

    /**
     * If the user would not exist an exception would be thrown
     */
    public function admin_user_exists()
    {
        Sentry::findUserByCredentials(array(
            'email'      => 'admin@example.com',
            'password'   => 'password'
        ));
    }

    /**
     * If the group would not exist an exception would be thrown
     */
    public function administrator_group_exists()
    {
        Sentry::findGroupByName('Administrator');
    }

    /**
     * If the group would not exist an exception would be thrown
     */
    public function owner_group_exists()
    {
        Sentry::findGroupByName('Owner');
    }

    /**
     * If the group would not exist an exception would be thrown
     */
    public function moderator_group_exists()
    {
        Sentry::findGroupByName('Moderator');
    }

}
