<?php

namespace App\Models;

class EmailToken extends Model
{
    protected $fillable = ['email', 'token'];
}
