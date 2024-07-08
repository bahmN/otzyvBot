<?php

use App\Http\Controllers\AdminController;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $response = Telegraph::botInfo()->send()->json();
    $response['url_bot'] = 'https://t.me/Blogrecall25bot';
    unset(
        $response['result']['can_join_groups'],
        $response['result']['can_read_all_group_messages'],
        $response['result']['supports_inline_queries'],
        $response['result']['can_connect_to_business']
    );
    return view('botInfo', ['data' => json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)]);
});

Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::post('/admin/approve', [AdminController::class, 'approve']);
Route::post('/admin/reject', [AdminController::class, 'reject']);
