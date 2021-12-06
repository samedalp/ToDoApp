<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $table = "task";
    protected $fillable = [
        'provider',
        'name',
        'level',
        'duration'
    ];


}
