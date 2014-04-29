<?php namespace Larapress\Commands;

use Cartalyst\Sentry\Groups\GroupInterface;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Config;
use Sentry;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{
    protected $email = 'admin@example.com';
    protected $password = 'password';
    protected $url;

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
     * Create a new command instance.
     *
     * @return InstallCommand
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = url(Config::get('larapress.urls.backend'));
    }

    /**
     * Create User Groups / Roles
     *
     * @return GroupInterface Returns the Administrator group
     */
    public function create_user_groups()
    {
        try {
            // Create the Administrator group
            $admin_group = Sentry::createGroup(
                array(
                    'name' => 'Administrator',
                    'permissions' => array(
                        'access.backend' => 1,
                        'administrator.edit' => 1,
                        'owner.add' => 1,
                        'owner.remove' => 1,
                        'owner.edit' => 1,
                        'moderator.add' => 1,
                        'moderator.remove' => 1,
                        'moderator.edit' => 1,
                        'self.remove' => 0,
                        'content.administrate' => 1,
                        'content.manage' => 1,
                        'configuration.edit' => 1,
                        'partial.add' => 1,
                        'partial.edit' => 1,
                    ),
                )
            );

            // Create the Owner group
            Sentry::createGroup(
                array(
                    'name' => 'Owner',
                    'permissions' => array(
                        'access.backend' => 1,
                        'administrator.edit' => 0,
                        'owner.add' => 0,
                        'owner.remove' => 0,
                        'owner.edit' => 1,
                        'moderator.add' => 1,
                        'moderator.remove' => 1,
                        'moderator.edit' => 1,
                        'self.remove' => 0,
                        'content.administrate' => 0,
                        'content.manage' => 1,
                        'configuration.edit' => 1,
                        'partial.add' => 0,
                        'partial.edit' => 1,
                    ),
                )
            );

            // Create the Moderator group
            Sentry::createGroup(
                array(
                    'name' => 'Moderator',
                    'permissions' => array(
                        'access.backend' => 1,
                        'administrator.edit' => 0,
                        'owner.add' => 0,
                        'owner.remove' => 0,
                        'owner.edit' => 0,
                        'moderator.add' => 0,
                        'moderator.remove' => 0,
                        'moderator.edit' => 0,
                        'self.remove' => 1,
                        'content.administrate' => 0,
                        'content.manage' => 1,
                        'configuration.edit' => 0,
                        'partial.add' => 0,
                        'partial.edit' => 0,
                    ),
                )
            );
        } catch (NameRequiredException $e) {
            $this->call('migrate:reset');
            $this->error('Name field is required');
            die();
        } catch (GroupExistsException $e) {
            $this->call('migrate:reset');
            $this->error('Group already exists');
            die();
        }

        return $admin_group;
    }

    /**
     * Add the admin user
     *
     * @param GroupInterface $admin_group The Administrator group
     * @return void
     */
    public function add_the_admin_user($admin_group)
    {
        try {
            // Create the user
            $user = Sentry::createUser(
                array(
                    'email' => $this->email,
                    'password' => $this->password,
                    'activated' => true,
                )
            );

            // Assign the group to the user
            $user->addGroup($admin_group);
        } catch (LoginRequiredException $e) {
            $this->call('migrate:reset');
            $this->error('Login field is required.');
            die();
        } catch (PasswordRequiredException $e) {
            $this->call('migrate:reset');
            $this->error('Password field is required.');
            die();
        } catch (UserExistsException $e) {
            $this->call('migrate:reset');
            $this->error('User with this login already exists.');
            die();
        } catch (GroupNotFoundException $e) {
            $this->call('migrate:reset');
            $this->error('Group was not found.');
            die();
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

        // Run migrations
        $this->call('migrate', array('--package' => 'cartalyst/sentry'));

        // Create User Groups / Roles
        $admin_group = $this->create_user_groups();

        // Add the admin user
        $this->add_the_admin_user($admin_group);

        /**
         * Notify the user about the successful install and tell him how to continue
         */
        $this->info(PHP_EOL . 'Installation complete!' . PHP_EOL);
        $this->info('Now please visit ' . $this->url . ' and login.' . PHP_EOL);
        $this->info('Credentials:');
        $this->info('E-Mail: ' . $this->email);
        $this->info('Password: ' . $this->password . PHP_EOL);
        $this->info('Make sure you instantly update your credentials!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('example', InputArgument::OPTIONAL, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
