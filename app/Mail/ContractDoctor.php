<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContractDoctor extends Mailable
{
    use Queueable, SerializesModels;

    protected array $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Send the email for contract with the doctor
        return $this->subject('Need support of customer from healthy social')
            ->from($this->details['from_email'], $this->details['from_user_name'])
            ->view('emails.contract-doctor');
    }
}