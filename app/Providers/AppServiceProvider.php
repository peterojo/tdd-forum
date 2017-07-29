<?php

namespace App\Providers;

use App\Models\Channel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer(['layouts.app', 'threads.create'], function($view) {
        	$channels = \Cache::rememberForever('channels', function () {
        		return Channel::all();
	        });

        	$view->with('channels', $channels);
        });
         # or
	    // \View::share('channels', Channel::all());
	    // // to just share the $channels variable with all views,
	    // this doesn't work for testing, runs before migrations
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
        	$this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
