<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;


class CarController extends Controller
{
    // View all available cars (frontend)
    public function AvailableCarList(){
        $cars = Car::where('availability', true)->get();
        return response()->json($cars);
    }

    // View car details (frontend)
    public function ShowCar($id){
        $car = Car::findOrFail($id);
        return response()->json($car);
        //return view('frontend.cars.show', compact('car'));
    }
}
