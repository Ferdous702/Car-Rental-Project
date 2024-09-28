<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Support\Facades\File;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{

    public function CarList(){
        $cars = Car::all();
        return response()->json($cars);
    }

    public function CreateCar(Request $request){
        //dd($request->all());

        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'car_type' => 'required|string',
            'daily_rent_price' => 'required|numeric',
            'availability' => 'required|boolean',
            'image' => 'required|image'
        ]);

        $img = $request->file('image');
        $t = time();
        $file_name = $img->getClientOriginalName();
        $img_name ="{$t}-{$file_name}";
        $img_url="uploads/{$img_name}";

        $img->move(public_path('uploads'),$img_name);

         Car::create([
            'name' => $request->input('name'),
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
            'car_type' => $request->input('car_type'),
            'daily_rent_price' => $request->input('daily_rent_price'),
            'availability' => $request->input('availability'),
            'image' => $img_url,

        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful'
        ],200);
    }

    public function UpdateCar(Request $request,$id){
        $car = Car::find($id);
        if($car){
            if($request->hasFile('image')){
                $img = $request->file('image');
                $t = time();
                $file_name = $img->getClientOriginalName();
                $img_name ="{$t}-{$file_name}";
                $img_url="uploads/{$img_name}";
                $img->move(public_path('uploads'),$img_name);


                // delete old file if file_path is provided
                if ($request->has('file_path')) {
                    $filePath = $request->input('file_path');
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }

                // update Car
                $car->update([
                    'name' => $request->input('name'),
                    'brand' => $request->input('brand'),
                    'model' => $request->input('model'),
                    'year' => $request->input('year'),
                    'car_type' => $request->input('car_type'),
                    'daily_rent_price' => $request->input('daily_rent_price'),
                    'availability' => $request->input('availability'),
                    'image' => $img_url,

                ]);
            }else{
                $car->update([
                    'name' => $request->input('name'),
                    'brand' => $request->input('brand'),
                    'model' => $request->input('model'),
                    'year' => $request->input('year'),
                    'car_type' => $request->input('car_type'),
                    'daily_rent_price' => $request->input('daily_rent_price'),
                    'availability' => $request->input('availability'),
                ]);

            }
            return response()->json([
                'status' => 'success',
                'message' => 'Car updated successfully'
            ],200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found'
            ], 404);
        }
    }

    public function DeleteCar(Request $request,$id){
        $car = Car::find($id);
        if($car){
            $filePath = $request->input('file_path');
            File::delete($filePath);
            $car->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'delete successfully'
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found'
            ], 404);
        }
    }


}












