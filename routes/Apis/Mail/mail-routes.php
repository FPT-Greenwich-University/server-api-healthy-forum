<?php

use App\Http\Controllers\Api\Mail\SendMailController;
use Illuminate\Support\Facades\Route;

Route::post('/contract-doctor', [SendMailController::class, 'sendEmailContract']);
