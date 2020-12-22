<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Announcment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function createAnnouncment(Request $request){
        $rules = [
            'title' => 'required|max:20|string',
            'desc' => 'required',
            'date'=>'required',
            'salary' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return  response($errors, 419);
        } else {

            Announcment::create([
                'title' => $request->title,
                'description' => $request->desc,
                'salary' => $request->salary,
                'deadline' => $request->date,
                'user_id' => auth()->user()->id,
            ]);

            return response('success', 200);
        }

    }
    public function getAnnouncment(){
        $announcments=Announcment::with('programmer','creator')->get();
        return $announcments;
    }
    public function save(Request $request){
        $id=$request->data['id'];
        $ann=Announcment::find($id);
        $ann->title=$request->data['title'];
        $ann->description=$request->data['description'];
        $ann->deadline=$request->data['deadline'];
        $ann->salary=$request->data['salary'];
        $ann->user_id=$request->data['user_id'];
        $ann->save();
        return $ann;
    }
    public function delete(Request $request){


        Announcment::where('id',$request->user_id)->delete();
        return $request->user_id;
    }
    public function take(Request $request){

        Announcment::where('id',$request->product)->update([
            'to_do_id'=>auth()->user()->id
        ]);
        return auth()->user()->id;
    }
    public function decline(Request $request){
        Announcment::where('id',$request->decline)->update([
            'to_do_id'=>null
        ]);
        return auth()->user()->id;
    }public function getToDo(){

//    $announcments=Announcment::with('programmer')->get();
//    return $announcments;
return "samo";

}
}
