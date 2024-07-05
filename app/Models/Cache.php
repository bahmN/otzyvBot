<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model {
    protected $table = 'cache';

    protected $primaryKey  = 'chat_id';

    protected $fillable = [
        'chat_id',
        'action'
    ];

    public $timestamps = false;
}
