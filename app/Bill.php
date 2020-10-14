<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //use HasFactory;
    protected $fillable = [
        'idUser',
        'dateOder',
        'addressShipping',
        'message'
    ];
    public function detail_bill_item()
    {
        return $this->belongsToMany('App\Item');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
