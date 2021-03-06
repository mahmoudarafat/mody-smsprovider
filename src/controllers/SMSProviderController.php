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
        $errors = [];
        return view('smsprovider::setup', compact('errors'));
    }

    public function editProviderConfig($provider_id)
    {
        $provider = Provider::findOrFail($provider_id);
        $data = [];

        $params = $provider->params;

        foreach ($params as $param) {
            $arr = [];
            $arr['key'] = $param->parameter;
            $arr['value'] = $param->value;

            $data[] = $arr;
        }

        return view('smsprovider::edit-provider', compact('provider', 'data'));
    }

    public function authProviders()
    {

        try {
            $guard = $this->getMyGuard();
            // if(auth()->guard($guard)->user()){
                $trytwo = Provider::where('user_id', auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
                ->where('group_id', session('group_id'))->paginate(20);
            // }else{
                // $trytwo = Provider::paginate(20);
            // }
            return $trytwo;
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function authProvidersView()
    {
        try {
            $title = trans('smsprovider::smsgateway.user_providers_title');
            $trytwo = $this->authProviders();
            return view('smsprovider::auth-providers', compact('trytwo', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function myGroupProviders()
    {
        $trytwo = Provider::where('group_id', session('group_id'))->paginate(20);
        return $trytwo;
    }

    public function myGroupProvidersView()
    {
        try {
            $title = trans('smsprovider::smsgateway.group_providers_title');
            $tryone = $this->myGroupProviders();
            return view('smsprovider::group-providers', compact('tryone', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function authTrashedProviders()
    {
        $guard = $this->getMyGuard();

        // if (auth()->guard($guard)->check()) {
            $trytwo = Provider::onlyTrashed()->where('user_id', auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
            ->where('group_id', session('group_id'))->paginate(20);
            return $trytwo;
        // } else {
            // return collect();
        // }

    }

    public function authTrashedProvidersView()
    {
        try {
            $title = trans('smsprovider::smsgateway.user_trashed_providers_title');
            $trytwo = $this->authTrashedProviders();
            return view('smsprovider::auth-providers', compact('trytwo', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        $guard = $this->getMyGuard();

        if ($provider) {
            DB::transaction(function () use ($provider_id, $default, $provider, $guard) {

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
                $this->recordTrack($tr['5'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null , $message_id ?? 0, $provider->id ?? 0, $description);
            });

            return true;
        } else {
            return false;
        }


    }

    public function submitSetup(Request $request)
    {
        $validation = $this->validateRequest($request);
        if($validation && is_array($validation) && $validation['status'] == false){
            return response()->json($validation);   
        }
        try {
            $guard = $this->getMyGuard();

            DB::transaction(function () use ($request, $guard) {

                `Save the provider data in providers table`;
                $provider = $this->storeProvider($request);

                `config parameters`;
                $names = $request->api_add_name ?? [];
                $values = $request->api_add_value ?? [];

                `Store provider parameters`;
                $this->storeAdditionalParams($provider->id, $names, $values);

                $tr = $this->trackArray();
                $description = 'new provider ' . $provider->company_name . ' [' . $provider->id . '] is added';
                $this->recordTrack($tr['1'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);

            });

            $plan = $this->getUserPlan();

            if ($plan == 'user') {
                return response()->json([
                    'status' => true,
                    'message' => trans('smsprovider::smsgateway.saved'),
                    'redirection' => route('smsprovider.providers.user-providers')
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => trans('smsprovider::smsgateway.saved'),
                    'redirection' => route('smsprovider.providers.group-providers')
                ]);
            }
        } catch (\Exception $e) {
            $errors = [trans('smsprovider::smsgateway.error')];
            $view = view('smsprovider::layouts.validation', compact('errors'))->render();
            return [
                'status' => false,
                'errors' => $errors,
                'view' => $view
            ];
        }
    }

    public function submitUpdate(Request $request)
    {
        $validation = $this->validateRequest($request);
        if($validation && is_array($validation) && $validation['status'] == false){
            return response()->json($validation);   
        }
        try {
         
            $guard = $this->getMyGuard();

            DB::transaction(function () use ($request, $guard) {

                `Save the provider data in providers table`;
                $provider = $this->updateProvider($request);

                `config parameters`;
                $names = $request->api_add_name ?? [];
                $values = $request->api_add_value ?? [];

                $provider->params()->forceDelete();

                `Store provider parameters`;
                $this->storeAdditionalParams($provider->id, $names, $values);

                $tr = $this->trackArray();
                $description = 'provider ' . $provider->company_name . ' [' . $provider->id . '] is updated';
                $this->recordTrack($tr['2'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);

            });

            $plan = $this->getUserPlan();

            if ($plan == 'user') {
                return response()->json([
                    'status' => true,
                    'message' => trans('smsprovider::smsgateway.saved'),
                    'redirection' => route('smsprovider.providers.user-providers')
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => trans('smsprovider::smsgateway.saved'),
                    'redirection' => route('smsprovider.providers.group-providers')
                ]);
            }

        } catch (\Exception $e) {
            $errors = [trans('smsprovider::smsgateway.error')];
            $view = view('smsprovider::layouts.validation', compact('errors'))->render();
            return [
                'status' => false,
                'errors' => $errors,
                'view' => $view
            ];
        }
    }

    public function sendNewSMS($message, $numbers)
    {
        $guard = $this->getMyGuard();

        $provider = $this->initProvider();
        $tr = $this->trackArray();
        if ($provider) {

            $method = $provider->http_method;
            $success_code = $provider->success_code;
            $url = $provider->api_url;

            $t = $this->generateSMSBody($message, $numbers);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url . '?' . $t);

            if($method != 'get')
            {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }

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
//                $respo = 'sent successfully';
                    $respo = '1';
                    $status = true;
                } else {
                    $status = false;
//                $respo = 'fail with code: ' . $code;
                    $respo = '0: ' . $code;
                }
            } else {
                $status = false;
                $code = 'NAN';
//            $respo = 'no response';
                $respo = '2';
            }


            $nums = explode(',', $numbers);

            foreach ($nums as $number) {
                $message_id = $this->saveMessageData($code, $message, $number, $status, auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $provider->id);
                $description = 'sending message to user';
                ##### I'm here!!!
                $this->recordTrack($tr['0'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id, $provider->id, $description);
            }

            return $respo;

        } else {
            return '3';
        }

    }

    public function softDeleteProvider($provider_id)
    {
        $guard = $this->getMyGuard();

        $provider = Provider::find($provider_id);
        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id, $guard) {
                $company_name = $provider->company_name;
                $provider->delete();
                $tr = $this->trackArray();
                $description = 'put provider ' . $company_name . '[' . $provider_id . '] in trash';
                $this->recordTrack($tr['3'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);
            });
            return true;
        } else {
            return false;
        }

    }

    public function forceDeleteProvider($provider_id)
    {
        $guard = $this->getMyGuard();

        $provider = Provider::find($provider_id);
        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id, $guard) {

                $company_name = $provider->company_name;
                $tr = $this->trackArray();
                $description = 'provider ' . $company_name . ' [' . $provider_id . '] is removed for god';
                $this->recordTrack($tr['4'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);
                $provider->forceDelete();
            });
            return true;
        } else {
            return false;
        }
    }

    public function recoverTrashedProvider($provider_id)
    {
        $provider = Provider::withTrashed()->find($provider_id);

        $guard = $this->getMyGuard();

        if ($provider) {
            \DB::transaction(function () use ($provider, $provider_id, $guard) {

                if ($provider->trashed()) {
                    $company_name = $provider->company_name;
                    $provider->restore();

                    $tr = $this->trackArray();
                    $description = 'provider ' . $company_name . ' [' . $provider_id . '] is restored from trash';
                    $this->recordTrack($tr['7'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);
                }
            });
            return true;
        } else {
            return false;
        }
    }

    public function removeDefault()
    {
        $guard = $this->getMyGuard();

        $provider = $this->initProvider();
        if ($provider) {
            if ($provider->isDefault()) {
                \DB::transaction(function () use ($provider, $guard) {
                    $company_name = $provider->company_name;
                    $provider_id = $provider->id;
                    $provider->default = false;
                    $provider->save();

                    $tr = $this->trackArray();
                    $description = 'provider ' . $company_name . ' [' . $provider_id . '] is not the default Provider any more';
                    $this->recordTrack($tr['6'], auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null, $message_id ?? 0, $provider->id ?? 0, $description);
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
            '0' => 'sending message',
            '1' => 'new provider setup',
            '2' => 'update provider setup',
            '3' => 'soft delete provider',
            '4' => 'destroy provider',
            '5' => 'set default',
            '6' => 'remove default',
            '7' => 'restore provider',
        ];
        return $tr;
    }

    public function recordTrack($type, $user_id = null, $message_id = null, $provider_id = null, $description = null)
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

    public function saveMessageData($code, $message, $number, $status, $user_id = null, $provider_id = 0)
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

    public function myGroupTrack()
    {
        $track = Track::where('group_id', session('group_id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return $track;
    }

    public function myGroupTrackView()
    {
        $track = $this->myGroupTrack();
        return view('smsprovider::track-group', compact('track'));
    }

    public function myTrack()
    {
        $guard = $this->getMyGuard();

        // if (auth()->guard($guard)->check()) {
            $track = Track::where('user_id', auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
            ->where('group_id', session('group_id'))
                ->orderBy('created_at', 'DESC')
                ->paginate(20);
            return $track;
        // } else {
            // return collect();
        // }
    }

    public function myTrackView()
    {
        $track = $this->myTrack();
        return view('smsprovider::track', compact('track'));
    }

    public function myLog()
    {
        $guard = $this->getMyGuard();

        // if (auth()->guard($guard)->check()) {
            $messages = Message::where('user_id', auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
            ->where('group_id', session('group_id'))
                ->orderBy('created_at', 'DESC')
                ->paginate(20);
            return $messages;
        // } else {
            // return collect();
        // }
    }

    public function myLogView(Request $request)
    {
        $guard = $this->getMyGuard();

        $messages = Message::where('user_id', auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
        ->where('group_id', session('group_id'))
                ->orderBy('created_at', 'DESC');

        if ($request->has('from_date') && !in_array($request->from_date, ['', null, '0'])) {
            $messages = $messages->where('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && !in_array($request->to_date, ['', null, '0'])) {
            $messages = $messages->where('created_at', '<=', $request->to_date);
        }
        $messages = $messages->paginate(15);
        return view('smsprovider::message-log', compact('messages'));
    }

    public function groupLogActivity()
    {
        $messages = Message::where('group_id', session('group_id'))
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return $messages;
    }

    public function groupLogActivityView(Request $request)
    {
        $messages = Message::where('group_id', session('group_id'))
            ->orderBy('created_at', 'DESC');

        if ($request->has('from_date') && !in_array($request->from_date, ['', null, '0'])) {
            $messages = $messages->where('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && !in_array($request->to_date, ['', null, '0'])) {
            $messages = $messages->where('created_at', '<=', $request->to_date);
        }
        $messages = $messages->paginate(15);
        return view('smsprovider::group-message-log', compact('messages'));
    }

    private function getMyGuard()
    {
        $g = config('smsgatewayConfig.guard');
        return $g ?? 'web';
    }

}
