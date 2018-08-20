<?php

Route::group(['middleware' => 'web', 'prefix' => 'smsprovider'], function () {
    Route::get('setup', 'mody\smsprovider\Facades\SMSProvider@configProvider');
    Route::get('user-providers', 'mody\smsprovider\Facades\SMSProvider@myProvidersView')->name('smsprovider.providers.auth_index');
    Route::get('user-trashed-providers', 'mody\smsprovider\Facades\SMSProvider@myTrashedProvidersView');
    Route::get('group-providers', 'mody\smsprovider\Facades\SMSProvider@groupProvidersView');
    Route::get('group-trashed-providers', 'mody\smsprovider\Facades\SMSProvider@groupTrashedProvidersView');
    Route::get('edit-provider/{provider_id}', 'mody\smsprovider\Facades\SMSProvider@editProvider')->name('smsprovider.providers.edit_provider');
    Route::post('submit_setup', 'mody\smsprovider\Facades\SMSProvider@submitProviderSetup')->name('smsprovider.submit_setup');

    Route::post('update_setup', 'mody\smsprovider\Facades\SMSProvider@updateProviderSetup')->name('smsprovider.providers.update_setup');

    //    Route::get('send', 'mody\smsprovider\controllers\SMSProvider@sendNewSMS')->name('smsprovider.send_sms');
    //    Route::get('test', 'mody\smsprovider\controllers\SMSProvider@test');

    Route::get('gg', function (){

//        return \mody\smsprovider\Facades\SMSProvider::groupProvidersView();


       $x = \mody\smsprovider\Facades\SMSProvider::sendSMS('test Me', '201065825376');
        dd($x);
    });

});