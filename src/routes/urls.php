<?php

Route::group(['middleware' => 'web'], function () {
    Route::get('setup', 'mody\smsgateway\Facades\SMSGateway@configProvider');
    Route::get('user-providers', 'mody\smsgateway\Facades\SMSGateway@myProvidersView')->name('smsgateway.providers.auth_index');
    Route::get('user-trashed-providers', 'mody\smsgateway\Facades\SMSGateway@myTrashedProvidersView');
    Route::get('group-providers', 'mody\smsgateway\Facades\SMSGateway@groupProvidersView');
    Route::get('group-trashed-providers', 'mody\smsgateway\Facades\SMSGateway@groupTrashedProvidersView');
    Route::get('edit-provider/{provider_id}', 'mody\smsgateway\Facades\SMSGateway@editProvider')->name('smsgateway.providers.edit_provider');
    Route::post('submit_setup', 'mody\smsgateway\Facades\SMSGateway@submitProviderSetup')->name('smsgateway.submit_setup');

    Route::post('update_setup', 'mody\smsgateway\Facades\SMSGateway@updateProviderSetup')->name('smsgateway.providers.update_setup');

    //    Route::get('send', 'mody\smsgateway\controllers\SMSGatewayController@sendNewSMS')->name('smsgateway.send_sms');
    //    Route::get('test', 'mody\smsgateway\controllers\SMSGateway@test');

    Route::get('gg', function (){
       $x = \mody\smsgateway\Facades\SMSGateway::sendSMS('test Me', '201065825376');
        dd($x);
    });

});