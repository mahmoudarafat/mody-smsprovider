<?php

namespace mody\smsprovider\controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use mody\smsprovider\Facades\SMSProvider;
use mody\smsprovider\Models\Provider;
use mody\smsprovider\Models\Template;


class SMSProviderAjaxController extends Controller
{
    public function recoverProvider(Request $request)
    {
        $provider_id = $request->provider_id;
        $do = SMSProvider::recoverProvider($provider_id);
        return json_encode($do);

    }

    public function trashProvider(Request $request)
    {
        $provider_id = $request->provider_id;
        $do = SMSProvider::deleteProvider($provider_id);
        return json_encode($do);
    }

    public function destroyProvider(Request $request)
    {
        $provider_id = $request->provider_id;
        $do = SMSProvider::destroyProvider($provider_id);
        return json_encode($do);
    }

    public function removeDefaultProvider(Request $request)
    {
        $provider_id = $request->provider_id;
        $provider = Provider::find($provider_id);
        if ($provider->isDefault()) {
            $do = SMSProvider::removeDefaultProvider();

            return json_encode($do);
        } else {
            return json_encode(false);
        }
    }

    public function setDefaultProvider(Request $request)
    {
        $provider_id = $request->provider_id;
        $do = SMSProvider::setDefaultProvider($provider_id);
        return json_encode($do);
    }




    public function changeTempStatus(Request $request)
    {
        $template_id = $request->template_id;
        $template = Template::find($template_id);
        $s = $template->status;
        $do = SMSProvider::changeTemplateStatus($template_id);
        return json_encode(['res' => $do, 'stat' => $s]);
    }

    public function restoreTemplate(Request $request)
    {
        $template_id = $request->template_id;
        $do = SMSProvider::recoverTemplate($template_id);
        return json_encode($do);
    }

    public function trashTemplate(Request $request)
    {
        $template_id = $request->template_id;
        $do = SMSProvider::trashTemplate($template_id);
        return json_encode($do);
    }

    public function destroyTemplate(Request $request)
    {
        $template = $request->template_id;
        $provider = Template::find($template);
        $do = SMSProvider::removeTemplate($template);
        return json_encode($do);

    }

}