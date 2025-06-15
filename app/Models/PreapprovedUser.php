<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreapprovedUser extends Model
{
    protected $fillable = [
        'roll',
        'registration_number',
    ];
}
