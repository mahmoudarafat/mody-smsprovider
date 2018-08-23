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
            Route::post('submit_setup', $SMSProvider . '@submitProviderSetup')->name('submit_setup');

            Route::get('user-providers', $SMSProvider . '@myProvidersView')->name('user-providers');
            Route::get('user-trashed-providers', $SMSProvider . '@myTrashedProvidersView');

            Route::get('group-providers', $SMSProvider . '@groupProvidersView')->name('group-providers');
            Route::get('group-trashed-providers', $SMSProvider . '@groupTrashedProvidersView');

            Route::get('edit-provider/{provider_id}', $SMSProvider . '@editProvider')->name('edit_provider');
            Route::post('update_setup', $SMSProvider . '@updateProviderSetup')->name('update_setup');

            Route::get('user-track', $SMSProvider . '@trackView')->name('user-track');
            Route::get('group-track', $SMSProvider . '@groupTrackView')->name('group-track');

            Route::get('user-log', $SMSProvider . '@logView')->name('user-log');
            Route::get('group-log', $SMSProvider . '@groupLogView')->name('group-log');

            
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
