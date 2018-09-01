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
 * username is your name columns to be displayed.
 * if track is true, activities will be saved in sms_provider_track_activity table.
 * user_model_namespace is your auth model. used in relationship with track table.
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

    /*
    * Are you in group or just you?
    */
    'plan' => 'user',

    /*
     * Should I store your/group actions in your database?
     */
    'track' => true,

    /*
     * your middlewares. ['web', 'auth', 'language', .....]
     */
    'middleware' => ['web', 'auth'],

    /*
     * Authentication guard you will use ['web, 'api', ....]
     */
    'guard' => 'web',

    /*
     * how can I call you? [$user->name]
     */
    'username' => 'name',

    /*
     * Where can I find you? [$user = User::find($my_id);]
     */
    'user_model_namespace' => 'App\User',
];
