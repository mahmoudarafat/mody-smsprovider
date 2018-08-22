<?php

namespace mody\smsprovider\Facades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use mody\smsprovider\controllers\SMSProviderController;

class SMSProvider extends SMSProviderController
{

    /**
     * @return new provider configuration view
     */
    public static function configProvider()
    {
        return (new parent())->index();
    }

    /**
     * @return provider configuration view/Edit
     */
    public static function editProvider($provider_id)
    {
        return (new parent())->editProviderConfig($provider_id);
    }

    /**
     * @return submit provider configuration
     */
    public function submitProviderSetup(Request $request)
    {
        return parent::submitSetup($request);
    }

    /**
     * @return submit provider configuration
     */
    public function updateProviderSetup(Request $request)
    {
        return parent::submitUpdate($request);
    }

    /**
     * @return sending new sms
     */
    public static function sendSMS($message, $numbers)
    {
        return (new parent())->sendNewSMS($message, $numbers);
    }

    /**
     * @return auth user providers configuration collection
     */
    public static function myProviders()
    {
        return (new parent())->authProviders();
    }

    /**
     * @return auth user trashed providers configuration collection
     */
    public static function myTrashedProviders()
    {
        return (new parent())->authTrashedProviders();
    }

    /**
     * @return auth user providers configuration view
     */
    public static function myProvidersView()
    {
        return (new parent())->authProvidersView();
    }

    /**
     * @return auth user trashed providers configuration view
     */
    public static function myTrashedProvidersView()
    {
        return (new parent())->authTrashedProvidersView();
    }

    /**
     * @return group providers configuration collection
     */
    public static function groupProviders()
    {
        return (new parent())->myGroupProviders();
    }

    /**
     * @return group providers configuration view
     */
    public static function groupProvidersView()
    {
        return (new parent())->myGroupProvidersView();
    }

    /**
     * @return group trashed providers configuration collection
     */
    public static function groupTrashedProviders()
    {
        return (new parent())->myGroupTrashedProviders();
    }

    /**
     * @return group providers configuration view
     */
    public static function groupTrashedProvidersView()
    {
        return (new parent())->myGroupTrashedProvidersView();
    }

    /**
     * @param $provider_id, set default provider
     */
    public static function setDefaultProvider($provider_id)
    {
        return (new parent())->markProviderAsDefault($provider_id);
    }

    /**
     * @param $provider_id, set default provider
     */
    public static function removeDefaultProvider()
    {
        return (new parent())->removeDefault();
    }


    /**
     * @param $provider_id, soft delete provider
     */
    public static function deleteProvider($provider_id)
    {
        return (new parent())->softDeleteProvider($provider_id);
    }


    /**
     * @param $provider_id, destroy provider
     */
    public static function destroyProvider($provider_id)
    {
        return (new parent())->forceDeleteProvider($provider_id);
    }

    /**
     * @param $provider_id, restore trashed provider
     * @return bool
     */
    public static function recoverProvider($provider_id)
    {
        return (new parent())->recoverTrashedProvider($provider_id);
    }


    /**
     * @return group track activity collection
     */
    public static function groupTrack()
    {
        return (new parent())->myGroupTrack();
    }

    /**
     * @return group track activity view
     */
    public static function groupTrackView()
    {
        return (new parent())->myGroupTrackView();
    }
    
    /**
     * @return user track activity collection
     */
    public static function track()
    {
        return (new parent())->myTrack();
    }

    /**
     * @return user track activity view
     */
    public static function trackView()
    {
        return (new parent())->myTrackView();
    }

    
}
