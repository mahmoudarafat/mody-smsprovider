<?php

namespace mody\smsprovider;

use Illuminate\Support\ServiceProvider;
use mody\smsprovider\commands\tableCommand;
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
        include __DIR__.'/helpers.php';
		$this->loadTranslationsFrom(__DIR__.'/lang', 'smsprovider');
		if(! file_exists(public_path('packages\mody\smsprovider\style.css')))
		{
			$this->publishes([
				__DIR__.'/assets' => public_path('packages\mody\smsprovider'),
			], 'public');
		}

		if(! file_exists(base_path('config/smsgatewayConfig.php')))
		{
			$this->publishes([ __DIR__.'/config/smsgatewayConfig.php' => base_path('config')]);
		}
        if ($this->app->runningInConsole()) {
            $this->commands([
                tableCommand::class
            ]);
        }

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
