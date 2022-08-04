<?php

namespace App\Http\Controllers\Api\Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mails\SendEmailContractRequest;
use App\Jobs\SendMailContract;
use Illuminate\Http\JsonResponse;

class SendMailController extends Controller
{
    public function sendEmailContract(SendEmailContractRequest $request): JsonResponse
    {
        $details = [
            'title' => $request->input('title'),
            'from_email' => $request->input('from_email'),
            'from_user_name' => $request->input('from_email_user_name'),
            'body' => $request->input('body'), // Doctor email
            'receiver_email' => $request->input('receiver_email')
        ];

        $this->dispatch(new SendMailContract($details));

        return response()->json('Send email success', 201);
    }
}
