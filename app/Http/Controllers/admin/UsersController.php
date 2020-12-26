<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        return response()->json("samo");
    }

    public function image(Request $request)
    {
        // $pathtoFile=$request->file('image')->store('images','public');
//        $request->file('image')->store('pictures');
        $samo = "";

        Storage::put('avatars/1', $request->image);
        // return  Storage::disk('local')->put('images', $request->image,);
//        $contents = ;  return Storage::get('avatars/1');
    }

    public function userInfo(Request $request)
    {
        $user = User::where('id', $request->user)->with('createdCars')->get();

        for ($i = 0; $i < count($user[0]->createdCars); $i++) {
            $user[0]->createdCars[$i]->images = Storage::get($user[0]->createdCars[$i]->images);
        }
        return $user;
    }

    public function getAll()
    {
        $users = User::get();

        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]->image) {
                $users[$i]->image = Storage::get($users[$i]->image . "/profile/");
            }
        }
        return $users;
    }

    public function edit($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function save(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->surname = $request->surname;
        $user->isActive = $request->isActive;
        $user->role = $request->role;
        $user->save();
        return $user;
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return $id;
    }
}
