<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'department',
        'featured_image_url',
        'content',
        'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
