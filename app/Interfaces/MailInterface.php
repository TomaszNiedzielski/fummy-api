<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface MailInterface
{
    /**
     * Check if key match to userId and confirm mail verification
     * 
     * @method  api/mail/confirm  POST
     * @access  public
     */
    public function confirmVerification(Request $request);

    /**
     * Send verification mail
     * 
     * @method api/mail/send/verification-mail
     * @access  public
     */
    public function sendVerificationMail();
}