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

}
