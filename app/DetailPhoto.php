<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPhoto extends Model
{
    use HasFactory;
    protected $fillable = [
        'namePhoto',
        'idItem'
    ];
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
