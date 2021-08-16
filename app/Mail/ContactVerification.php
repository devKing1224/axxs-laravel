<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactVerification extends Mailable {

    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content) {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        // config(['mail.username' =>  env('MAIL_USERNAME')]);
        // config(['mail.password' => env('MAIL_PASSWORD')]);
        
        return $this->subject('Verify your email.')
                        ->markdown('emails.orders.emailverify')
                        ->with('content', $this->content);
    }

}
