<?php

$SMSProvider = 'mody\smsprovider\Facades\SMSProvider';
$TemplatesController = 'mody\smsprovider\controllers\SMSProviderTemplatesController';
$SMSProviderAjaxController = 'mody\smsprovider\controllers\SMSProviderAjaxController';

$middleware = config('smsgatewayConfig.middleware');

$route =
    Route::group([
        'middleware' => $middleware,
        'prefix' => 'smsprovider',
        'as' => 'smsprovider.providers.'
    ],
        function () use ($SMSProvider, $SMSProviderAjaxController, $TemplatesController) {
            Route::get('setup', $SMSProvider . '@configProvider')->name('setup');
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

            Route::get('user-templates', $TemplatesController . '@userTempsView')->name('user-templates');
            Route::get('group-templates', $TemplatesController . '@groupTempsView')->name('group-templates');

            Route::get('user-trashed-templates', $TemplatesController . '@userTrashTempsView')->name('user-trashed-templates');
            Route::get('group-trashed-templates', $TemplatesController . '@groupTrashTempsView')->name('group-trashed-templates');

            Route::get('store-templates', $TemplatesController . '@createTemplates')->name('create-user-templates');
            Route::post('store-templates', $TemplatesController . '@storeTemplates')->name('store-user-templates');

            Route::get('edit-template/{template_id}', $TemplatesController . '@editTemplate')->name('edit_template');
            Route::post('update-template', $TemplatesController . '@updateTemplate')->name('update_template');


            Route::group([
                'prefix' => 'ajax',
                'as' => 'ajax.'
            ], function () use ($SMSProviderAjaxController) {
                Route::post('restore-provider', $SMSProviderAjaxController . '@recoverProvider')->name('restore-provider');
                Route::post('trash-provider', $SMSProviderAjaxController . '@trashProvider')->name('trash-provider');
                Route::post('destroy-provider', $SMSProviderAjaxController . '@destroyProvider')->name('destroy-provider');
                Route::post('set-default-provider', $SMSProviderAjaxController . '@setDefaultProvider')->name('set-default-provider');
                Route::post('remove-default-provider', $SMSProviderAjaxController . '@removeDefaultProvider')->name('remove-default-provider');

                Route::post('change-temp-status', $SMSProviderAjaxController . '@changeTempStatus')->name('status-template');
                Route::post('trash-template', $SMSProviderAjaxController . '@trashTemplate')->name('trash-template');
                Route::post('destroy-template', $SMSProviderAjaxController . '@destroyTemplate')->name('destroy-template');
                Route::post('restore-template', $SMSProviderAjaxController . '@restoreTemplate')->name('restore-template');

            });

        });
