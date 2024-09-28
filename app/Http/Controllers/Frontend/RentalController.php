<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function booking(Request $request)
    {
        $car = Car::findOrFail($request->car_id);

        if ($car->availability){
            $rental = Rental::create([
                'user_id' => auth()->id(),
                'car_id' => $car->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_cost' => $request->total_cost,
            ]);

            // Mark car as unavailable
            $car->availability = false;
            return response()->json($rental);

        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found'
            ], 404);
        }

    }

    // View customer’s bookings
    public function myBookings()
    {
        //$rentals = User::where('id', auth()->id())->with('rentals')->get();
        $rentals = auth()->user()->rentals;
        return response()->json($rentals);
        //return view('frontend.rentals.index', compact('rentals'));
    }
}
