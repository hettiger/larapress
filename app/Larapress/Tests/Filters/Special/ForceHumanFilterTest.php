<?php namespace Larapress\Tests\Filters;

use Captcha;
use InvalidArgumentException;
use Larapress\Filters\Special\ForceHumanFilter;
use Larapress\Tests\TestCase;
use Route;

class ForceHumanFilterTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Route::enableFilters();
    }

    public function test_can_return_null_if_captcha_is_not_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(false);

        $filter = new ForceHumanFilter;
        $this->assertInternalType('null', $filter->filter());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot redirect to an empty URL.
     */
    public function test_can_redirect_back_if_the_captcha_is_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(true);

        $filter = new ForceHumanFilter;
        $filter->filter();
    }

    public function test_can_flash_an_error_message_to_the_session_if_the_captcha_is_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(true);

        $filter = new ForceHumanFilter;

        try
        {
            $filter->filter();
        }
        catch (InvalidArgumentException $e)
        {
            $this->assertEquals('Cannot redirect to an empty URL.', $e->getMessage());
        }

        $this->assertSessionHas('error', 'Please verify that you are human first.');
    }

}
