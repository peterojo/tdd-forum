<?php

namespace Tests;

use App\Exceptions\Handler;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp() {
	    parent::setUp();
		$this->disableExceptionHandling();
    }

    protected function disableExceptionHandling()
    {
		$this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

	    $this->app->instance(ExceptionHandler::class, new BlankHandler);
    }

    protected function withExceptionHandling()
    {
		$this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

		return $this;
    }

	public function signIn($user = null)
	{
		$user = $user?: create('App\Models\User');

		$this->actingAs($user);

		return $this;
    }
}

class BlankHandler extends Handler {
	public function __construct() {}
	public function report(Exception $e) {}
	public function render($request, Exception $e) {
		throw $e;
	}
}
