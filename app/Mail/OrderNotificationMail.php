<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $deadline;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontendUrl = \Config::get('constans.frontend_url');

        return $this->markdown('mails.order_notification')
            ->subject('Masz nowe zlecenie.')
            ->with('url', $frontendUrl.'/orders')
            ->with('deadline', $this->deadline);
    }
}
