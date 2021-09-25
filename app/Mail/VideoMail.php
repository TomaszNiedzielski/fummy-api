<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $videoName;
    protected $nick;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $videoName, string $nick)
    {
        $this->videoName = $videoName;
        $this->nick = $nick;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontendUrl = \Config::get('constans.frontend_url');

        return $this->markdown('mails.video')
            ->subject('Twoje zamówienie zostało zrealizowane.')
            ->with([
                'url' => $frontendUrl.'/u/'.$this->nick.'/video/'.$this->videoName,
                'nick' => $this->nick
            ]);
    }
}
