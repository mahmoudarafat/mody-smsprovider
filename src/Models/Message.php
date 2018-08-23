<?php

namespace mody\smsprovider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use softDeletes;

    protected $table = 'sms_provider_messages';

    protected $dates = ['deleted_at'];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'sms_provider_id', 'id');
    }


    public function user()
    {
        $auth = config('smsgatewayConfig.user_model_namespace');
        return $this->belongsTo($auth);
    }

    public function getUser()
    {
        $name = config('smsgatewayConfig.username');
        $user = $this->user;
        $username = $user->$name ?? 'NAN';
        return $username;
    }

}
