<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Developer extends Model
{
    protected $table = "developer";

    protected $fillable = [
        'name',
        'level'
    ];

}
