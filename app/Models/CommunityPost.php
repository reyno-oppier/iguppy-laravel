<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    protected $fillable = ['user_id', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reactions() {
        return $this->hasMany(CommunityReaction::class, 'post_id');
    }
}

