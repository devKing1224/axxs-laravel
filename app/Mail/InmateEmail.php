<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InmateEmail extends Mailable {

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
        config(['mail.username' => $this->content['from']]);
        config(['mail.password' => $this->content['password']]);

        return $this->subject($this->content['title'])
                        ->markdown('emails.orders.shipped')
                        ->from($this->content['from'])
                        ->with('content', $this->content);
    }

}
