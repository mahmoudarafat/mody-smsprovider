<?php

namespace mody\smsprovider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use softDeletes;

    protected $table = 'sms_provider_track_activity';

    protected $dates = ['deleted_at'];

}
