<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    protected $table = 'user_ip';

    protected $fillable = [
        'user_ip',
        'count',
    ];
}
