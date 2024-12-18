<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class my_client extends Model
{
    protected $table = 'my_clients';
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_project',
        'self_capture',
        'client_prefix',
        'client_logo',
        'address',
        'phone_number',
        'city',
    ];
}