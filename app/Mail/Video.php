<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Video extends Mailable
{
    use Queueable, SerializesModels;

    protected $videoName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $videoName)
    {
        $this->videoName = $videoName;
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
            ->subject('O to zamówione przez ciebie video.')
            ->with('url', $frontendUrl.'/u/'.auth()->user()->nick.'/video/'.$this->videoName);
    }
}
