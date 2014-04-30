<?php namespace Larapress\Tests;

ini_set('memory_limit', '256M');

use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Mockery;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TestCase extends LaravelTestCase
{

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__ . '/../../../bootstrap/start.php';
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

}
