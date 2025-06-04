<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publish_buku extends Model
{

    protected $casts = [
    'created_at' => 'datetime',
];
    protected $table = 'publish_books';
}
