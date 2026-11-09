<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\checklogin;



Route::middleware(CheckRole::class)->group(function () {
    // Route::get('/Product', function () {
    //     return view('Product');
    // });

    Route::get('/Invoice', function () {
        return view('Invoices');
    });


    Route::get('/', [ProductController::class, 'index'])->name('dashboard');
    //User
    Route::post('/updateuser', [UserController::class, 'update'])->name('user.update');
    Route::get('/User', [UserController::class, 'Showuser'])->name('user.show');
    Route::post('/deleteuser', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/adduser', [UserController::class, 'Add'])->name('user.add');
    //Product
    Route::get('/Product', [ProductController::class, 'Showproducts'])->name('product.show');
    Route::post('/addpro', [ProductController::class, 'Add'])->name('product.add');
    Route::put('/editpro/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::get('/Product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    Route::get('/Pos', [PosController::class, 'showPOS'])->name('pos.show');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/generate-qr', [PosController::class, 'generateQR'])->name('pos.generate-qr');
    Route::post('/pos/check-payment', [PosController::class, 'checkPayment'])->name('pos.check-payment');
    // Route::get('/filter-sales', [DashboardController::class, 'filterSales']);

    Route::get('/Invoice', [ProductController::class, 'invoices'])->name('invoice.show');
    Route::get('/Calendar', [ProductController::class, 'salesCalendar'])->name('calendar.show');
    Route::post('/settings/update', [ProductController::class, 'updateSetting'])->name('settings.update');
});

Route::get('/Login', function () {
    return view('Login');
})->name('login');
Route::post('/dologin', [checklogin::class, 'Checklogin'])->name('login.post');
// Route::get('/Pos', function () {
//     return view('page.Pos');
// });
Route::get('/logout', [checklogin::class, 'logout'])->name('logout');