<?php

namespace App\Interfaces;

use App\Http\Requests\MailingAddressRequest;

interface MailingAddressInterface
{
    /**
     * Add email to list
     * 
     * @method  POST  api/mailing-address
     */
    public function addEmail(MailingAddressRequest $request);
}