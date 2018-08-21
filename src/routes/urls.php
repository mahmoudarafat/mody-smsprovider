<?php

$SMSProvider = 'mody\smsprovider\Facades\SMSProvider';
$SMSProviderAjaxController = 'mody\smsprovider\controllers\SMSProviderAjaxController';
$route =
    Route::group([
        'middleware' => 'web',
        'prefix' => 'smsprovider',
        'as' => 'smsprovider.providers.'
    ],
        function () use ($SMSProvider, $SMSProviderAjaxController) {
            Route::get('setup', $SMSProvider . '@configProvider');
            Route::get('user-providers', $SMSProvider . '@myProvidersView')->name('auth_index');
            Route::get('user-trashed-providers', $SMSProvider . '@myTrashedProvidersView');
            Route::get('group-providers', $SMSProvider . '@groupProvidersView');
            Route::get('group-trashed-providers', $SMSProvider . '@groupTrashedProvidersView');
            Route::get('edit-provider/{provider_id}', $SMSProvider . '@editProvider')->name('edit_provider');
            Route::post('submit_setup', $SMSProvider . '@submitProviderSetup')->name('submit_setup');

            Route::post('update_setup', $SMSProvider . '@updateProviderSetup')->name('update_setup');

            Route::group([
                'prefix' => 'ajax',
                'as' => 'ajax.'
            ], function () use ($SMSProviderAjaxController) {
                Route::post('restore-provider', $SMSProviderAjaxController . '@recoverProvider')->name('restore-provider');
                Route::post('trash-provider', $SMSProviderAjaxController . '@trashProvider')->name('trash-provider');
                Route::post('destroy-provider', $SMSProviderAjaxController . '@destroyProvider')->name('destroy-provider');
                Route::post('set-default-provider', $SMSProviderAjaxController . '@setDefaultProvider')->name('set-default-provider');
                Route::post('remove-default-provider', $SMSProviderAjaxController . '@removeDefaultProvider')->name('remove-default-provider');
            });

        });