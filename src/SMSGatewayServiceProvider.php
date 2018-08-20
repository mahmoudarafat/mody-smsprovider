<?php

namespace mody\smsprovider;

use Illuminate\Support\ServiceProvider;
use mody\smsprovider\controllers\SMSGatewayController;
use mody\smsprovider\controllers\SMSProviderController;

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
		$this->loadTranslationsFrom(__DIR__.'/lang', 'smsprovider');
		if(! file_exists(public_path('packages\mody\smsprovider\style.css')))
		{
			$this->publishes([
				__DIR__.'/assets' => public_path('packages\mody\smsprovider'),
			], 'public');
		}
		/*
		if(! file_exists(base_path('resources/views/lang/ar/smsprovider.php')))
		{
			$this->publishes([ __DIR__.'/lang/ar' => base_path('resources/views/lang/ar')]);
		}
		if(! file_exists(base_path('resources/views/lang/en/smsprovider.php')))
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
        $this->app->alias(SMSProviderController::class, 'SMSProvider');
        $this->app->make('mody\smsprovider\controllers\SMSProviderController');
        $this->loadViewsFrom(__DIR__.'/views', 'smsprovider');

    }
}
