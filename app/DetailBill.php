<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBill extends Model
{
    //use HasFactory;
    protected $fillable = [
        'idBill',
        'idItem',
        'counting'
    ];
}
