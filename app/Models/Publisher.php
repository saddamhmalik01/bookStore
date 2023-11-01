<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;
    protected $hidden = [
        'description',
        'founded_at',
        'website',
        'contact_email',
        'phone_number',
        'address',
        'created_at',
        'updated_at'
    ];
}
