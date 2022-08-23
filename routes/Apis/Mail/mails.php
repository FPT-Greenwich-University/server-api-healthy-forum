<?php

use App\Http\Controllers\Api\MailController;
use Illuminate\Support\Facades\Route;

Route::post('/contract-doctor', [MailController::class, 'sendEmailContract']);
