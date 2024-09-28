<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CarController as AdminCarController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Frontend\RentalController as FrontendRentalController;
use App\Http\Controllers\Frontend\CarController as FrontendCarController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/user-login',[AdminCustomerController::class,'UserLogin']);
Route::get('/user-registration',[AdminCustomerController::class,'UserRegistration']);

Route::middleware(['role:admin'])->group(function () {
    // Admin Customer controller
    Route::get('admin/customers',[AdminCustomerController::class,'CustomerList']);
    Route::post('admin/delete-customers/{id}',[AdminCustomerController::class,'DeleteCustomer']);

    // Admin Rentals Controller
    Route::get('/admin/rentals',[AdminRentalController::class,'RentalsList']);
    Route::post('admin/create-rentals',[AdminRentalController::class,'CreateRentals']);
    Route::post('admin/delete-rentals/{id}',[AdminRentalController::class,'RentalDelete']);
    Route::post('admin/rentals-update/{id}',[AdminRentalController::class,'RentalUpdate']);

    // Admin Car Controller
    Route::get('/admin/cars',[AdminCarController::class,'CarList']);
    Route::post('/admin/cars/create',[AdminCarController::class,'CreateCar']);
    // Edit has to show
    Route::post('/admin/cars/update/{id}',[AdminCarController::class,'UpdateCar']);
    Route::post('/admin/cars/delete/{id}',[AdminCarController::class,'DeleteCar']);
});






Route::middleware(['role:customer'])->group(function () {
    // Frontend Controller
    // CarController
    Route::get('/cars', [FrontendCarController::class, 'AvailableCarList']);
    Route::get('/cars/{id}', [FrontendCarController::class, 'ShowCar']);


    // PageController


    // Rentals Controller
    Route::post('/booked', [FrontendRentalController::class, 'booking']);
    Route::get('/my-bookings', [FrontendRentalController::class, 'myBookings']);

});





