<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcment extends Model
{
    use HasFactory;
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'salary',
        'user_id',
        'to_do_id'
    ];
    public function programmer(){
        return $this->belongsTo('App\Models\User','to_do_id','id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
