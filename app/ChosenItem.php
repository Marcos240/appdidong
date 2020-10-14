<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChosenItem extends Model
{
    //use HasFactory;
    protected $fillable = [
        'idItem',
        'idUser',
        'count'
    ];
}
