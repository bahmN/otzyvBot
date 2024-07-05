<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model {
    protected $fillable = [
        'chat_id',
        'blogger_name'
    ];

    public $timestamps = false;
}
