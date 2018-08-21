<?php

namespace mody\smsprovider\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mody\smsprovider\Models\Message;
use mody\smsprovider\Models\Provider;
use mody\smsprovider\Models\Track;
use mody\smsprovider\traits\DatabaseConfig;
use mody\smsprovider\traits\ProviderConfig;
use mody\smsprovider\traits\SMSGateway;
use DB;

class SMSProviderController extends Controller
{
    use DatabaseConfig, ProviderConfig, SMSGateway;

    public function index()
    {
        session()->put('group_id', 2);
        return view('smsprovider::setup');
    }

    public function editProviderConfig($provider_id)
    {
        $provider = Provider::findOrFail($provider_id);

        return view('smsprovider::edit-provider', compact('provider'));
    }

    public function authProviders()
    {
        $trytwo = Provider::where('user_id', auth()->user()->id)->paginate(20);
        return $trytwo;
    }

    public function authProvidersView()
    {
        $title = trans('smsprovider::smsgateway.user_providers_title');
        $trytwo = $this->authProviders();
        return view('smsprovider::auth-providers', compact('trytwo', 'title'));
    }

    public function myGroupProviders()
    {
        $trytwo = Provider::where('group_id', session('group_id'))->paginate(20);
        return $trytwo;
    }

    public function myGroupProvidersView()
    {
        $title = trans('smsprovider::smsgateway.group_providers_title');
        $tryone = $this->myGroupProviders();
        return view('smsprovider::group-providers', compact('tryone', 'title'));
    }

    public function authTrashedProviders()
    {
        $trytwo = Provider::onlyTrashed()->where('user_id', auth()->user()->id)->paginate(20);
        return $trytwo;
    }

    public function authTrashedProvidersView()
    {
        $title = trans('smsprovider::smsgateway.user_trashed_providers_title');
        $trytwo = $this->authTrashedProviders();
        return view('smsprovider::auth-providers', compact('trytwo', 'title'));
    }

    public function myGroupTrashedProviders()
    {
        $trytwo = Provider::onlyTrashed()->where('group_id', session('group_id'))->paginate(20);
        return $trytwo;
    }

    public function myGroupTrashedProvidersView()
    {
        $title = trans('smsprovider::smsgateway.group_trashed_providers_title');
        $tryone = $this->myGroupTrashedProviders();
        return view('smsprovider::group-providers', compact('tryone', 'title'));
    }

    public function markProviderAsDefault($provider_id)
    {

        $default = $this->initProvider();

        $provider = Provider::find($provider_id);

        if ($provider) {
            DB::transaction(function () use ($provider_id, $default, $provider) {

                $company_name = $provider->company_name;

                if ($default) {
                    $def_name = $default->company_name;
                    $default->default = false;
                    $default->save();
                }

                $provider->default = true;
                $provider->save();


                $tr = $this->trackArray();
                $description = 'set provider ' . $company_name . ' [' . $provider_id . '] as Default';
                if ($default) {
                    $description .= 'instead of ' . $def_name . '.';
                }
                $this->recordTrack($tr['5'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);
            });

            return true;
        } else {
            return false;
        }


    }

    public function submitSetup(Request $request)
    {

        `check if tables exists or create them
            providers table, provider parameters, messges table, template messages table

            `;

        try {

            $this->configProviderTable();
            $this->configAdditionalParams();
            $this->configMessagesTable();
            $this->configSavedMessages();
            $this->configTrackMessages();

            $this->validateRequest($request);

            DB::transaction(function () use ($request) {

                `Save the provider data in providers table`;
                $provider = $this->storeProvider($request);

                `config parameters`;
                $names = $request->api_add_name ?? [];
                $values = $request->api_add_value ?? [];

                array_push($names, $request->username_column);
                array_push($values, $request->username_value);
                array_push($names, $request->api_password_column);
                array_push($values, $request->api_password_value);

                `Store provider parameters`;
                $this->storeAdditionalParams($provider->id, $names, $values);

                $tr = $this->trackArray();
                $description = 'new provider ' . $provider->company_name . ' [' . $provider->id . '] is added';
                $this->recordTrack($tr['1'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);

            });

            return redirect()->route('smsprovider.providers.auth_index')->with([
                'success' => trans('smsprovider::smsgateway.saved')
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => trans('smsprovider::smsgateway.error')
            ]);
        }
    }

    public function sendNewSMS($message, $numbers)
    {

        $provider = $this->initProvider();
        $method = $provider->http_method;
        $success_code = $provider->success_code;
        $url = $provider->api_url;

        $t = $this->generateSMSBody($message, $numbers);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . '?' . $t);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $t);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $t);

        // Allowing cUrl funtions 30 second to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Waiting 30 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        $response_string = curl_exec($ch);

        curl_close($ch);

        $xx = $this->explodeX(['|', ',', '-'], $response_string);

        if ($xx) {
            $code = $xx[0];
            if (strpos($code, $success_code) !== false) {
                $respo = 'sent successfully';
                $status = true;
            } else {
                $status = false;
                $respo = 'fail with code: ' . $code;
            }
        } else {
            $status = false;
            $code = 'NAN';
            $respo = 'no response';
        }

        $tr = $this->trackArray();

        $nums = explode(',', $numbers);

        foreach ($nums as $number) {
            $message_id = $this->saveMessageData($code, $message, $number, $status, auth()->user()->id ?? 0, $provider->id);
            $description = 'sending message to user';
            ##### I'm here!!!
            $this->recordTrack($tr['0'], auth()->user()->id ?? 0, $message_id, $provider->id, $description);
        }

        return $respo;
    }

    public function softDeleteProvider($provider_id)
    {
        $provider = Provider::find($provider_id);
        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id) {
                $company_name = $provider->company_name;
                $provider->delete();
                $tr = $this->trackArray();
                $description = 'put provider ' . $company_name . '[' . $provider_id . '] in trash';
                $this->recordTrack($tr['3'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);
            });
            return true;
        } else {
            return false;
        }

    }

    public function forceDeleteProvider($provider_id)
    {
        $provider = Provider::find($provider_id);
        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id) {

                $company_name = $provider->company_name;
                $provider->forceDelete();
                $tr = $this->trackArray();
                $description = 'provider ' . $company_name . ' [' . $provider_id . '] is removed for god';
                $this->recordTrack($tr['4'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);
            });
            return true;
        } else {
            return false;
        }
    }

    public function recoverTrashedProvider($provider_id)
    {
        $provider = Provider::withTrashed()->find($provider_id);

        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id) {

                if ($provider->trashed()) {
                    $company_name = $provider->company_name;
                    $provider->restore();

                    $tr = $this->trackArray();
                    $description = 'provider ' . $company_name . ' [' . $provider_id . '] is restored from trash';
                    $this->recordTrack($tr['7'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);

                }
            });
            return true;
        } else {
            return false;
        }
    }

    public function removeDefault()
    {
        $provider = $this->initProvider();
        if ($provider) {
            if ($provider->isDefault()) {
                \DB::transaction(function () use ($provider) {
                    $company_name = $provider->company_name;
                    $provider_id = $provider->id;
                    $provider->default = false;
                    $provider->save();

                    $tr = $this->trackArray();
                    $description = 'provider ' . $company_name . ' [' . $provider_id . '] is not the default Provider any more';
                    $this->recordTrack($tr['6'], auth()->user()->id ?? 0, $message_id ?? 0, $provider->id ?? 0, $description);
                });
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function trackArray()
    {
        $tr = [
            '0' => 'sending message', ###
            '1' => 'new provider setup',
            '2' => 'update provider setup',
            '3' => 'soft delete provider',  ###
            '4' => 'destroy provider', ###
            '5' => 'set default', ###
            '6' => 'remove default', ###
            '7' => 'restore provider', ###
        ];
        return $tr;
    }

    public function recordTrack($type, $user_id = 0, $message_id = null, $provider_id = null, $description = null)
    {
        $tr = $this->getTrackStatus();

        if ($tr) {
            $track = new Track();
            $track->type = $type;
            $track->user_id = $user_id;
            $track->message_id = $message_id;
            $track->group_id = session('group_id');
            $track->sms_provider_id = $provider_id;
            $track->description = $description;
            $track->save();
        }
    }

    public function saveMessageData($code, $message, $number, $status, $user_id = 0, $provider_id = 0)
    {
        $msg = new Message();
        $msg->message = $message;
        $msg->client_number = $number;
        $msg->status_code = $code;
        $msg->user_id = $user_id;
        $msg->sms_provider_id = $provider_id;
        $msg->status = $status;
        $msg->group_id = session('group_id');
        $msg->save();
        return $msg->id;

    }

}