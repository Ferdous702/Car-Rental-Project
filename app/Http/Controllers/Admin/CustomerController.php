<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    public function UserRegistration(Request $request){
        try {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'role' => 'customer'
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ],200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed'
            ],200);

        }
    }

    function UserLogin(Request $request){
        $user = User::where('email', $request->input('email'))->first();
        if($user && $user->password == $request->input('password')){
            Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'user log in successfully'
            ],200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }

    public function CustomerList(){
        $customers = User::where('role', 'customer')->get();
        return response()->json($customers);
    }

    // Admin can delete a customer account
    public function DeleteCustomer($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

    }

}
