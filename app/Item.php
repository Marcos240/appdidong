<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'idSize',
        'cost',
        'description',
        'avatar',
        'idCategory',
        'liked'
    ];
    public function size()
    {
        return $this->belongsTo('App\Size');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    public function detail_photo()
    {
        return $this->hasMany('App\DetailPhoto');
    }
    public function chosen_item_user()
    {
        return $this->hasMany('App\User');
    }
    public function liked_user_user()
    {
        return $this->belongsToMany('App\User');
    }
    public function detail_bill_bill()
    {
        return $this->belongsToMany('App\Bill');
    }
}
