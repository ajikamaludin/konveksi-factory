<?php

namespace App\Models;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phonenumber',
        'emails',
    ];
}
