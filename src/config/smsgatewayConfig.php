<?php

return [

  /*
 * smsprovider package provides multiple connection with many sms providers.
 * just set your provider configuration and start sending messages.
 *
 * two officially plans:
 *      [user, group]
 *   user plan is one user provider, that works only for one user [authenticated user].
 *   group is multiple user provider, that services a company users maybe,
 *      that shares the same sms provider.
 *
 *  with group plan, your providers will have an account id number column.
 *  when calling your SMSProvider::configProvider() method,
 *      you will need this session to be set: session()->put('group_id', $group_id);
 *
 *
 * package will create five tables .
 *      [
 *          'sms_providers' => 'container of providers you have',
 *          'sms_providers_additional_params' => 'necessary parameters we need for sending sms',
 *          'sms_provider_messages' => 'messages you sent either success of failed with error codes',
 *          'sms_direct_messages' => 'template messages you created for quick sending'
 *          'sms_provider_track_activity' => 'track user activity while using package methods'
 *      ]
 */
    
    'plan' => 'user',
    'track' => true,
];
