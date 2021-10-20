<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $userId;
    protected $userName;
    protected $key; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $userId, string $userName, string $key)
    {
        $this->userId = $userId;
        $this->userName = $userName;
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
        
        return $this->markdown('mails.verify')
            ->subject('Weryfikacja adresu e-mail.')
            ->with('userName', $this->userName)
            ->with('url', $frontendUrl.'/mail/verify?id='.$this->userId.'&key='.$this->key);
    }
}
