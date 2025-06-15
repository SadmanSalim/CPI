<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $fillable = [
        'current_job_title',
        'current_job_company',
        'current_job_location',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
