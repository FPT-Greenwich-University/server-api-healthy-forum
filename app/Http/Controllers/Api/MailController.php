<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mails\SendEmailContractRequest;
use App\Jobs\SendMailContract;
use Illuminate\Http\JsonResponse;

class MailController extends Controller
{
    final public function sendEmailContract(SendEmailContractRequest $request): JsonResponse
    {
        // Send mail with queue job (background task)
        dispatch(new SendMailContract([
            'title' => $request->input('title'),
            'from_email' => $request->input('from_email'),
            'from_user_name' => $request->input('from_email_user_name'),
            'body' => $request->input('body'), // Doctor email
            'receiver_email' => $request->input('receiver_email')
        ]));

        return response()->json('Send email success', 201); // Return 201 created success status
    }
}