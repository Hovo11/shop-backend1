<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','log','signUp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return string
     */
    public function log($id){
           $user=User::find($id);
            return $user;
//        return $id.name;

    }

//        $user= new User();
//        $user->name=
//        $user->surname=$request->surname;
//        $user->age=$request->age;
//        $user->password= Hash::make($request->password);
//        $user->save();
    public function signUp(Request $request) {

        $rules = [
            'name' => 'required|max:20|string',
            'surname' => 'required|max:20|string',
            'age'=>'numeric|required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' =>'required|min:8|string',
           // 'confirm_password' =>'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return  response($errors, 419);
        } else {

            User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'age' => $request->age,
                'password' => Hash::make($request->password),
            ]);

            return response('success', 200);
        }
    }
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
