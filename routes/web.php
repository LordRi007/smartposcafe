<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
Route::get('/', [UserController::class, 'Count'])->name('dashboard');

// Route::get('/Product', function () {
//     return view('Product');
// });

Route::get('/Login', function () {
    return view('Login');
});
// Route::get('/Pos', function () {
//     return view('page.Pos');
// });
//User
Route::post('/updateuser', [UserController::class, 'update'])->name('user.update');
Route::get('/User', [UserController::class, 'Showuser']);
Route::post('/adduser', [UserController::class, 'Add']);
Route::post('/deleteuser', [UserController::class, 'destroy'])->name('user.destroy');
//Product
Route::get('/Product', [ProductController::class, 'Showproducts']);
Route::post('/addpro', [ProductController::class, 'Add']);
Route::put('/editpro/{id}', [ProductController::class, 'update']);
Route::get('/Product/delete/{id}', [ProductController::class, 'delete']);
Route::get('/Pos', [PosController::class, 'showPOS']);
Route::post('/Pos/checkout', [PosController::class, 'checkout']);

