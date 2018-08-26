<?php

namespace mody\smsprovider\Facades;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use mody\smsgateway\Facades\SMSGateway;
use mody\smsprovider\controllers\SMSProviderController;
use mody\smsprovider\controllers\SMSProviderTemplatesController;

class SMSProvider extends SMSProviderController
{
    public function __construct(SMSProviderTemplatesController $template)
    {
        $this->template = $template;
    }

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
     * @param $provider_id , set default provider
     */
    public static function setDefaultProvider($provider_id)
    {
        return (new parent())->markProviderAsDefault($provider_id);
    }

    /**
     * @param $provider_id , set default provider
     */
    public static function removeDefaultProvider()
    {
        return (new parent())->removeDefault();
    }


    /**
     * @param $provider_id , soft delete provider
     */
    public static function deleteProvider($provider_id)
    {
        return (new parent())->softDeleteProvider($provider_id);
    }


    /**
     * @param $provider_id , destroy provider
     */
    public static function destroyProvider($provider_id)
    {
        return (new parent())->forceDeleteProvider($provider_id);
    }

    /**
     * @param $provider_id , restore trashed provider
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

    /**
     * @return user messages log activity collection
     */
    public static function log()
    {
        return (new parent())->myLog();
    }

    /**
     * @return user track activity view
     */
    public static function logView()
    {
        return (new parent())->myLogView();
    }


    /**
     * @return user messages log activity collection
     */
    public static function groupLog()
    {
        return (new parent())->groupLogActivity();
    }

    /**
     * @return user track activity view
     */
    public static function groupLogView()
    {
        return (new parent())->groupLogActivityView();
    }

    /* =-=-=-=-=-=-=-=-=-=-=-=-=-TEMPLATES-=-==-=-=-=-=-=-=-=-=-=-=-=-= */

    /**
     * @return user templates
     */
    public static function templates()
    {
        return (new SMSProviderTemplatesController())->userTemps();
    }

    /**
     * @return group templates
     */
    public static function groupTemplates()
    {
        return (new SMSProviderTemplatesController())->groupTemps();
    }

    /**
     * @return user trashed templates
     */
    public static function trashedTemplates()
    {
        return (new SMSProviderTemplatesController())->userTrashTemps();
    }

    /**
     * @return group trashed templates
     */
    public static function groupTrashedTemplates()
    {
        return (new SMSProviderTemplatesController())->groupTrashTemps();
    }

    /**
     * @return store new templates
     */
    public static function storeTemplates($templates_array)
    {
        return (new SMSProviderTemplatesController())->storeArrayTemplates($templates_array);
    }

    /**
     * @return user templates view
     */
    public static function templatesView()
    {
        return (new SMSProviderTemplatesController())->userTempsView();
    }

    /**
     * @return group templates view
     */
    public static function groupTemplatesView()
    {
        return (new SMSProviderTemplatesController())->groupTempsView();
    }

    /**
     * @return user trashed templates view
     */
    public static function trashTemplatesView()
    {
        return (new SMSProviderTemplatesController())->userTrashTempsView();
    }

    /**
     * @return group trashed templates view
     */
    public static function groupTrashTemplatesView()
    {
        return (new SMSProviderTemplatesController())->groupTrashTempsView();
    }

    /**
     * @return change template status
     */
    public static function changeTemplateStatus($template_id)
    {
        return (new SMSProviderTemplatesController())->changeTempStat($template_id);
    }

    /**
     * @return recover template
     */
    public static function recoverTemplate($template_id)
    {
        return (new SMSProviderTemplatesController())->recoverATemplate($template_id);
    }

    /**
     * @return trash template
     */
    public static function trashTemplate($template_id)
    {
        return (new SMSProviderTemplatesController())->trashATemplate($template_id);
    }

    /**
     * @return remove template
     */
    public static function removeTemplate($template_id)
    {
        return (new SMSProviderTemplatesController())->removeATemplate($template_id);
    }

}
