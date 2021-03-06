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

Route::get('/', function () {
    $signatureList = App\DriftersWormholeSystems::SIGNATURE_LIST;
    $signatureList[] = '无流浪洞';
    return view('welcome', [
        'systemOptions' => App\NeedWatchSystemMap::toJson(),
        'signatureList' => $signatureList,
    ]);
});
Route::post('/report', [App\Http\Controllers\WelcomeController::class, 'report'])->name('report');

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/drifters-wormhole-state', [App\Http\Controllers\HomeController::class, 'getDriftersWormholeState'])
    ->name('drifters-wormhole-state');
Route::get('/system-history/{systemName?}', [App\Http\Controllers\HomeController::class, 'getSystemHistory'])
    ->name('system-history');
Route::post('/report/delete/{id?}', [App\Http\Controllers\HomeController::class, 'deleteReport'])
    ->name('delete-report');
