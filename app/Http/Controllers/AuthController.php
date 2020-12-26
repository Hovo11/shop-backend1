<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signUp','checkEmailCode','forgotPassword','checkCode','changePassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return string
     */

    public function forgotPassword(Request $request){
        $email = $request->email;
        $user = User::where('email',  $email)->count();
        if($user > 0)
        {
            $details = [
                'title' => 'Code ',
                'body' => 'you need to write this code for new password'
            ];
            $x =rand(1000,9999);

            Storage::put('verifyPasswordCode', $x);
            Storage::put('verifyEmail', $email);
             Mail::to("$email")->send(new TestMail($details, $x));
            return response(true,200);
        }
        else
           return response("Email doesn't exist", 419);
    }
    public function checkCode(Request $request){
        $x=Storage::get('verifyPasswordCode');
        if ($request->code == $x){
            return response(true,200);
        }
        else
            return response("Wrong Code ", 419);
    }
    public function changePassword(Request $request){
        $email=Storage::get('verifyEmail');
        User::where('email',$email)->update([
            'password'=>Hash::make($request->password['password'])
        ]);
        return response(true,200);
    }
    public function checkEmailCode(Request $request){
     $x=Storage::get('my_info/code');
     $name=Storage::get('my_info/name');
     $surname=Storage::get('my_info/surname');
     $phone=Storage::get('my_info/phone');
     $address=Storage::get('my_info/address');
     $image=Storage::get('my_info/image');
     $email=Storage::get('my_info/email');
     $password=Storage::get('my_info/password');
     $type=Storage::get('my_info/type');
       if ( $request->code == $x){
           $now = now()->timestamp;
           $mYProfileImage='images/'.$now;
           Storage::put($mYProfileImage.'/profile/', $image);
           User::create([
               'name' => $name,
               'surname' => $surname,
               'email' => $email,
               'image' => "$mYProfileImage",
               'phone' => (string)$phone,
               'address' => $address,
               'type' => $type,
               'password' => Hash::make($password),
           ]);


       }
        return  "samo";
    }


    public function signUp(Request $request) {

        $rules = [
            'name' => 'required|max:20|string',
            'surname' => 'required|max:20|string',
            'phone'=>'numeric|required',
            'address'=>'required',

            'type'=>'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' =>'required|min:8|string',
           // 'confirm_password' =>'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return  response($errors, 419);
        } else {


            $details = [
                'title' => 'Mail from Media',
                'body' => 'this is for testing mail using gmail'
            ];
            $x =rand(1000,9999);
            Storage::put('my_info/code', $x);
            Storage::put('my_info/name', $request->name);
            Storage::put('my_info/surname', $request->surname);
            Storage::put('my_info/email', $request->email);
            Storage::put('my_info/address', $request->address);
            Storage::put('my_info/phone', $request->phone);
            Storage::put('my_info/image', $request->image);
            Storage::put('my_info/type',  $request->type);
            Storage::put('my_info/password', $request->password);
            $email = $request->email;
            return Mail::to("$email")->send(new TestMail($details, $x));
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
        if (auth()->user()->image){
        auth()->user()->image=Storage::get(auth()->user()->image."/profile/");}
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
