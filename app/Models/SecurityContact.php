<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityContact extends Model
{
    use HasFactory;
    // app/Models/SecurityContact.php
    protected $fillable = ['name', 'phone', 'is_active'];
    protected $casts =[
        'is_active'=> 'boolean',
    ];
}
