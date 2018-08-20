<?php

namespace mody\smsprovider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProviderParameter extends Model
{
    use SoftDeletes;

    protected $table = 'sms_provider_additional_params';

    protected $dates = ['deleted_at'];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'sms_provider_id', 'id');
    }
}
