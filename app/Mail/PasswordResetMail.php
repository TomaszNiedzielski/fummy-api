<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $key;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontendUrl = \Config::get('constans.frontend_url');

        return $this->markdown('mails.password_reset')
            ->subject('Reset hasła do konta.')
            ->with('url', $frontendUrl.'/password/change?key='.$this->key);
    }
}
