<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        'nameSize'
    ];
    public function item()
    {
        return $this->hasMany('App\Item');
    }
}
