<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json("samo");
    }
    public function getAll(){
        $users=User::get();
        return $users;
    }
    public function edit($id){
        $user=User::find($id);
        return $user;
    }
}
