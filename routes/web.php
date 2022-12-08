<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'welcome']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');


//********************//
// User's route
Route::get('/user', [App\Http\Controllers\userController::class, 'show'])->name('users');
Route::get('/detailU', [App\Http\Controllers\userController::class, 'detail'])->name('userDetail');
Route::get('/profile', function () {  // call profile page
                        return view('users.profile');
                    });
Route::post('/addUser', [App\Http\Controllers\userController::class, 'add']); // add new user
Route::post('/editUser/{id}', [App\Http\Controllers\userController::class, 'edit']); // edit user
Route::post('/editProfile', [App\Http\Controllers\userController::class, 'editProfile']); // edit user
Route::get('/deleteU', [App\Http\Controllers\userController::class, 'delete'])->name('delete.user'); // delete user info

//********************//
// Movie's route
Route::get('/movie', [App\Http\Controllers\movieController::class, 'show']);
Route::get('/now', [App\Http\Controllers\movieController::class, 'now']);
Route::get('/today', [App\Http\Controllers\movieController::class, 'today']);
Route::get('/thisWeek', [App\Http\Controllers\movieController::class, 'thisWeek']);
Route::get('/like/{mov_id}/{page}', [App\Http\Controllers\movieController::class, 'like'])->middleware('auth');
Route::get('/view/{mov_id}', [App\Http\Controllers\movieController::class, 'view']);
Route::get('/detailM/{id}', [App\Http\Controllers\movieController::class, 'detail']);
Route::get('/addM', [App\Http\Controllers\movieController::class, 'addMovie'])->middleware('auth');
Route::get('/editM/{id}', [App\Http\Controllers\movieController::class, 'editMovie'])->middleware('auth');
// Route::get('/seats/{id}', [App\Http\Controllers\ticketController::class, 'seats'])->middleware('auth');
Route::get('/seats/{id}', [App\Http\Controllers\movieController::class, 'seats'])->middleware('auth');

Route::post('/addMovie', [App\Http\Controllers\movieController::class, 'add'])->middleware('auth'); // add new movie
Route::post('/editMovie/{id}', [App\Http\Controllers\movieController::class, 'edit'])->middleware('auth');// edit movie
Route::get('/saveEditM', [App\Http\Controllers\movieController::class, 'saveEdit']); // edit movie info
Route::get('/deleteMovie/{id}', [App\Http\Controllers\movieController::class, 'delete'])->middleware('auth'); // delete movie info
Route::get('/movieByCatagory/{catagory}', [App\Http\Controllers\movieController::class, 'movieByCatagory'])->middleware('auth'); // delete movie info

Route::get('/viewTrailer', function () {
    return view('movies.movieModal');
});
Route::get('/403', function () {
    return view('accessX');
});
Route::get('/mm/{mov}', [App\Http\Controllers\movieController::class, 'checkTodays']);


Route::get('/coupon', [App\Http\Controllers\couponController::class, 'show'])->middleware('auth');
Route::post('/createCoupon', [App\Http\Controllers\couponController::class, 'createCoupon'])->middleware('auth');
Route::post('/updateCoupon/{coupon_id}', [App\Http\Controllers\couponController::class, 'updateCoupon'])->middleware('auth');
Route::get('/deleteCoupon/{id}', [App\Http\Controllers\couponController::class, 'deleteCoupon'])->middleware('auth');

Route::get('/ticket', [App\Http\Controllers\ticketController::class, 'show'])->middleware('auth');
Route::get('/userTicket', [App\Http\Controllers\ticketController::class, 'showMine'])->middleware('auth');
Route::post('/createTicket', [App\Http\Controllers\ticketController::class, 'createTicket'])->middleware('auth');
Route::post('/updateTicket/{ticket_id}', [App\Http\Controllers\ticketController::class, 'updateTicket'])->middleware('auth');
Route::get('/deleteTicket/{id}', [App\Http\Controllers\ticketController::class, 'deleteTicket'])->middleware('auth');
Route::get('/ticketValue/{selected}', [App\Http\Controllers\ticketController::class, 'ticketValue'])->middleware('auth');
Route::get('/ticketTime/{movie}', [App\Http\Controllers\ticketController::class, 'ticketTime'])->middleware('auth');

Route::get('/catagories', [App\Http\Controllers\catagoryController::class, 'show'])->middleware('auth');
Route::post('/addCatagory', [App\Http\Controllers\catagoryController::class, 'addCatagory'])->middleware('auth');
Route::post('/updateCatagory/{catagory_id}', [App\Http\Controllers\catagoryController::class, 'updateCatagory'])->middleware('auth');
Route::get('/deleteCatagory/{id}', [App\Http\Controllers\catagoryController::class, 'deleteCatagory'])->middleware('auth');

Route::post('/buyT/{movie}', function () {  // call profile page
    return view('movie.ticket');
});
Route::post('/buyTicket/{movie}', [App\Http\Controllers\ticketController::class, 'buyTicket'])->middleware('auth');
Route::get('/cancelTicket/{movie}/{page}', [App\Http\Controllers\ticketController::class, 'cancelTicket'])->middleware('auth');

Route::get('/show', [App\Http\Controllers\showController::class, 'show'])->middleware('auth');
Route::post('/addShow', [App\Http\Controllers\showController::class, 'addShow'])->middleware('auth');
Route::post('/updateShow/{show_id}', [App\Http\Controllers\showController::class, 'updateShow'])->middleware('auth');
Route::get('/deleteShow/{id}', [App\Http\Controllers\showController::class, 'deleteShow'])->middleware('auth');

Route::get('/feedback', [App\Http\Controllers\feedbackController::class, 'show'])->middleware('auth');
Route::post('/giveFeedback', [App\Http\Controllers\feedbackController::class, 'send'])->middleware('auth');

Route::get('/searchUser/{input?}', [App\Http\Controllers\userController::class, 'searchUser'])->middleware('auth');
Route::get('/searchCatagory/{input?}', [App\Http\Controllers\catagoryController::class, 'searchCatagory'])->middleware('auth');
Route::get('/searchTicket/{input?}', [App\Http\Controllers\ticketController::class, 'searchTicket'])->middleware('auth');
Route::get('/st/{input?}', [App\Http\Controllers\ticketController::class, 'searchTicket'])->middleware('auth');
Route::get('/searchShow/{input?}', [App\Http\Controllers\showController::class, 'searchShow'])->middleware('auth');
Route::get('/searchMovie/{input?}', [App\Http\Controllers\movieController::class, 'searchMovie'])->middleware('auth');

Route::get('/myaccount', [App\Http\Controllers\userController::class, 'myaccount'])->middleware('auth');
Route::post('/changePassword', [App\Http\Controllers\userController::class, 'changePassword']);


Route::get('/cp/{movie}/{z}', [App\Http\Controllers\ticketController::class, 'occupied']);
Route::get('/countFeedback', [App\Http\Controllers\feedbackController::class, 'newFeedback'])->middleware('auth');
Route::get('/report', [App\Http\Controllers\HomeController::class, 'report'])->middleware('auth');
Route::get('/report_request', [App\Http\Controllers\HomeController::class, 'reportRequest'])->middleware('auth');
