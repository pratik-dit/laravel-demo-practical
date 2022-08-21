<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendForgotPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $message)
    {
        $this->message    = $message;

        $this->from( config('app.mail_from'),'DemoProject');
        $this->subject("Temporary Password.");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return  $this->view('mail.forgot_password_email')->with([
            "name"              => $this->message['name'],
            "email"             => $this->message['email'],
            "password"          => $this->message['password'],
        ]);
    }
}
