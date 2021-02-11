<?php

namespace mody\smsprovider\traits;

use mody\smsprovider\Models\Provider;
use mody\smsprovider\Models\ProviderParameter;

trait ProviderConfig
{

    protected function storeProvider($request)
    {

        $guard = $this->getMyGuard();

        $provider = new Provider();
        $provider->company_name = $request->api_company;
        $provider->api_url = $request->api_url;
//        $provider->username = $request->api_username;
//        $provider->password = $request->api_password;
        $provider->destination_attr = $request->api_destination;
        $provider->message_attr = $request->api_message;
        $provider->success_code = $request->api_success_code;
        $provider->unicode = $request->api_unicode ? 1 : 0;
        $provider->http_method = $request->api_method;
        $provider->default = $this->isDefaultProvider();
        $provider->group_id = session('group_id');
        $provider->user_id = auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null;
        $provider->save();
        return $provider;
    }

    protected function updateProvider($request)
    {
        $provider = Provider::findOrFail($request->provider_id);
        $provider->company_name = $request->api_company;
        $provider->api_url = $request->api_url;
        $provider->destination_attr = $request->api_destination;
        $provider->message_attr = $request->api_message;
        $provider->success_code = $request->api_success_code;
        $provider->unicode = $request->api_unicode ? 1 : 0;
        $provider->http_method = $request->api_method;
        $provider->save();
        return $provider;
    }

    protected function storeAdditionalParams($provider_id, $names, $values)
    {
        $guard = $this->getMyGuard();

        foreach ($names as $key => $name) {
            if (!in_array($name, ['', null])) {
                $params = new ProviderParameter();
                $params->parameter = $name;
                $params->value = $values[$key];
                $params->group_id = session('group_id');
                $params->user_id = auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null;
                $params->sms_provider_id = $provider_id;
                $params->save();
            }
        }
    }

    public function validateRequest($request)
    {
        $rules = [
            'api_username' => 'string',
            'api_password' => 'string',
            'api_company' => 'required',
//            'api_url' => 'required|unique:sms_providers,api_url',
            'api_url' => 'required',
            'api_method' => 'required',
            'api_destination' => 'required',
            'api_message' => 'required',
            'api_success_code' => 'required',
        ];

        $messages = [
            'api_username' => '',
            'api_password' => '',
            'api_company.required' => trans('smsprovider::smsgateway.attributes.company_name'),
            'api_url.required' => trans('smsprovider::smsgateway.attributes.url_required'),
//            'api_url.unique' => trans('smsprovider::smsgateway.attributes.url_unique'),
            'api_method.required' => trans('smsprovider::smsgateway.attributes.http_method'),
            'api_destination.required' => trans('smsprovider::smsgateway.attributes.destination_attr'),
            'api_message.required' => trans('smsprovider::smsgateway.attributes.message_attr'),
            'api_success_code.required' => trans('smsprovider::smsgateway.attributes.success_code'),
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_data = [];
            foreach ($errors->all() as $error) {
                array_push($error_data, $error);
            } 
            $errors = $error_data;
            $view = view('smsprovider::layouts.validation', compact('errors'))->render();
            return [
                'status' => false,
                'errors' => $errors,
                'view' => $view
            ];
        }
    }

    private function multiexplode($delimiters, $string)
    {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

    private function getMyGuard()
    {
        $g = config('smsgatewayConfig.guard');
        return $g ?? 'web';
    }

    private function isDefaultProvider(){
        $guard = $this->getMyGuard();
        return Provider::where('default', 1)
            ->where('group_id', session('group_id'))
            ->where('user_id',  auth()->guard($guard)->user() ? auth()->guard($guard)->user()->id : null)
            ->first() ? 0 : 1;   
    }
}