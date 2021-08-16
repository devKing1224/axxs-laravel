<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RevertMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this->subject($this->content['subject'])
                        ->markdown('emails.orders.test')
                        ->with('content', $this->content);
          if (isset($this->content['attach'])) {
            foreach ($this->content['attach'] as $key=>$file) { 
            $message->attach($file['link']); // attach each file
            } 
                        }              
                       
        return $message;;
    }
}
