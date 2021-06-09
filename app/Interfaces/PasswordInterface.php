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
     * Reset password
     * 
     * @method  api/password/reset POST
     * @access  public
     */
    public function reset(Request $request);

    /**
     * Update password
     * 
     * @method api/password/update  POST
     * @access  public
     * 
     * @param   string  $currentPassword
     * @param   string  $newPassword
     */
    public function update(Request $request);
}