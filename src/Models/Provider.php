<?php

namespace mody\smsgateway\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use softDeletes;

    protected $table = 'sms_providers';

    protected $dates = ['deleted_at'];

    public function params()
    {
        return $this->hasMany(ProviderParameter::class, 'sms_provider_id', 'id');
    }

    public function isDefault()
    {
        if($this->default) {
            return true;
        }else{
            return false;
        }
    }

    public function messages(){
        return $this->hasMany(Message::class, 'sms_provider_id', 'id');
    }
}
