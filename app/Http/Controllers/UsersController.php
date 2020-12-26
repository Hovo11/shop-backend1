<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function save(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        if ($request->name != null) {
            $user->name = $request->name;
        }
        if ($request->email != null) {

            $user->email = $request->email;
        }
        if ($request->phone != null) {

            $user->phone = $request->phone;
        }
        $user->save();
        return $user;
    }
}
