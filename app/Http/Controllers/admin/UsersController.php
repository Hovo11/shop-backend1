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
    public function save(Request $request,$id){
        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->age=$request->age;
        $user->surname=$request->surname;
        $user->type=$request->type;
        $user->save();
        return $user;
    }
    public function delete($id){
        User::where('id',$id)->delete();
        return $id;
    }
}
