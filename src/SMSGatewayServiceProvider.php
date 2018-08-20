<?php

namespace mody\smsgateway;

use Illuminate\Support\ServiceProvider;
use mody\smsgateway\controllers\SMSGatewayController;

class SMSGatewayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes/urls.php';
		$this->loadTranslationsFrom(__DIR__.'/lang', 'smsgateway');
		if(! file_exists(public_path('packages\mody\smsgateway\style.css')))
		{
			$this->publishes([
				__DIR__.'/assets' => public_path('packages\mody\smsgateway'),
			], 'public');
		}
		/*
		if(! file_exists(base_path('resources/views/lang/ar/smsgateway.php')))
		{
			$this->publishes([ __DIR__.'/lang/ar' => base_path('resources/views/lang/ar')]);
		}
		if(! file_exists(base_path('resources/views/lang/en/smsgateway.php')))
		{
			$this->publishes([ __DIR__.'/lang/en' => base_path('resources/views/lang/en')]);
		}
		*/
	}
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(SMSGatewayController::class, 'SMSGateway');
        $this->app->make('mody\smsgateway\controllers\SMSGatewayController');
        $this->loadViewsFrom(__DIR__.'/views', 'smsgateway');

    }
}
