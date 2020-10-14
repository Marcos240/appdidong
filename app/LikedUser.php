<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikedUser extends Model
{
    //use HasFactory;
    protected $fillable = [
        'idItem',
        'idUser'
    ];
}
