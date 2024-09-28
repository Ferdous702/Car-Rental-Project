<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{

    public function RentalsList()
    {
        $rentals = Rental::with('car', 'user')->get();
        return response()->json($rentals);
    }

    public function CreateRentals(Request $request){
        //DB::beginTransaction();
        try {
            // Validate request data
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'car_id' => 'required|exists:cars,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'total_cost' => 'required|numeric|min:0',
            ]);

            // Check if the car is available for the rental period
            $car = Car::find($request->car_id);  // we passed car_id see bellow with request input
            if ($car->availability === false) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'The car is not available for the selected dates.',
                ], 400);
            }

            // Create new rental
            $rental = Rental::create([
                'user_id' => $request->input('user_id'),
                'car_id' => $request->input('car_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'total_cost' => $request->input('total_cost'),
            ]);

            // Set car availability to false after booking
            $car->update(['availability' => false]);

            //DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Rental created successfully.',
                'data' => $rental,
            ], 201);


        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rental creation failed.',
            ], 500);
        }
    }

    public function RentalDelete($id){
        //DB::beginTransaction();
        try {
            // Find rental
            $rental = Rental::find($id);
            if (!$rental) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Rental not found',
                ], 404);
            }
            // Set car availability back to true before deleting the rental
            $car = Car::find($rental->car_id);
            $car->update(['availability' => true]);

            // Delete the rental
            $rental->delete();
            //DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Rental deleted successfully.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Rental deletion failed.',
            ], 500);
        }
    }

//    public function RentalUpdate(Request $request, $id)
//    {
//        try {
//            // Validate request data
//            $request->validate([
//                'start_date' => 'required|date',
//                'end_date' => 'required|date|after:start_date',
//                'total_cost' => 'required|numeric|min:0',
//            ]);
//
//            // Find rental
//            $rental = Rental::find($id);
//
//            if (!$rental) {
//                return response()->json([
//                    'status' => 'failed',
//                    'message' => 'Rental not found',
//                ], 404);
//            }
//
//            // Update the rental
//            $rental->update([
//                'start_date' => $request->input('start_date'),
//                'end_date' => $request->input('end_date'),
//                'total_cost' => $request->input('total_cost'),
//            ]);
//
//            return response()->json([
//                'status' => 'success',
//                'message' => 'Rental updated successfully.',
//                'data' => $rental,
//            ], 200);
//
//        } catch (Exception $e) {
//            return response()->json([
//                'status' => 'failed',
//                'message' => 'Rental update failed.',
//            ], 500);
//        }
//    }


}
