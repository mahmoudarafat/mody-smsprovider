<?php

namespace mody\smsprovider\controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use mody\smsprovider\Facades\SMSProvider;
use mody\smsprovider\Models\Provider;


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

}