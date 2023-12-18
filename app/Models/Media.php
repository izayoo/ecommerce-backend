<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'filename', 'mime', 'status', 'expires_at'];

    protected $hidden = ['updated_at'];
}