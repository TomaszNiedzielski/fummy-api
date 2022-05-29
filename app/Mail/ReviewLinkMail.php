<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected $reviewSlot
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontendUrl = \Config::get('constans.frontend_url');

        return $this->markdown('mails.review_order')
            ->subject('Zostaw opinię.')
            ->with([
                'url' => $frontendUrl.'/u/'.$this->reviewSlot->nick.'/review?key='.$this->reviewSlot->key,
                'influencerNick' => $this->reviewSlot->nick
            ]);
    }
}
