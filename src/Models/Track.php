<?php

namespace mody\smsprovider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use softDeletes;

    protected $table = 'sms_provider_track_activity';

    protected $dates = ['deleted_at'];

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
