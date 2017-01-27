<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmoConfig extends Model
{
    protected $fillable = ["name", "subdomain", "login", "hash"];
}
