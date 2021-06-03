<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PasswordInterface
{
    /**
     * Send mail with reset link
     * 
     * @method  api/password/send-reset-link  POST
     * @access  public
     */
    public function sendResetLink(Request $request);

    /**
     * Change password
     * 
     * @method  api/password/change POST
     * @access  public
     */
    public function change(Request $request);
}