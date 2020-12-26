<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'make',
        'year',
        'partNumber',
        'name',
        'images',
        'user_id',
        'to_do_id'
    ];
    public function customer(){
        return $this->belongsTo('App\Models\User','to_do_id','id');
    }

    public function owner(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
