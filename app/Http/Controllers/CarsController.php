<?php

namespace App\Http\Controllers;
use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\Announcment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarsController extends Controller
{
    public function AddCar(Request $request){
        $rules = [
            'model' => 'required',
            'make' => 'required',
            'name' => 'required',
//            'images'=>'required',
            'year'=>'required',
            'partNumber' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return  response($errors, 419);
        } else {
            $mYCarsImage= auth()->user()->image;
            $now = now()->timestamp;
            $myCars="$mYCarsImage"."/cars/".$now;
            Storage::put($myCars, $request->images);

            Car::create([
                'model' => $request->model,
                'make' => $request->make,
                'name' => $request->name,
                'images'=>$myCars,
                'year' => $request->year,
                'partNumber' => $request->partNumber,

                'user_id' => auth()->user()->id,
            ]);

            return response('success', 200);
        }

    }
    public function getMyCars (){

        $id=auth()->user()->id;
        $cars=Car::where('user_id',$id)->get();
        for($i=0;$i<count($cars);$i++){
            $cars[$i]->images=Storage::get($cars[$i]->images);
        }
        return $cars;
    }
    public function getCars(){

        $cars=Car::with('customer','owner')->get();
        for($i=0;$i<count($cars);$i++){
            $cars[$i]->images=Storage::get($cars[$i]->images);
        }
        return $cars;
    }
    public function save(Request $request){
        $id=$request->data['id'];
        $car=Car::find($id);
        $car->model=$request->data['model'];
        $car->name=$request->data['name'];
        $car->partNumber=$request->data['partNumber'];
        $car->save();
        return $car;
    }
    public function delete(Request $request){


        Car::where('id',$request->user_id)->delete();
        return $request->user_id;
    }
    public function take(Request $request){

        Car::where('id',$request->product)->update([
            'to_do_id'=>auth()->user()->id
        ]);
        return auth()->user()->id;
    }
    public function decline(Request $request){
        Car::where('id',$request->decline)->update([
            'to_do_id'=>null
        ]);
        return auth()->user()->id;
    }public function getToDo(){

//    $announcments=Announcment::with('programmer')->get();
//    return $announcments;
return "samo";

}
}
