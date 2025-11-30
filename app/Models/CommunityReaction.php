<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityReaction extends Model
{
    protected $fillable = ['post_id', 'user_id'];

    public function post() {
        return $this->belongsTo(CommunityPost::class);
    }
}

