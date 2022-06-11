<?php

namespace App\Http\Controllers;

use App\Interfaces\MailingAddressInterface;
use App\Http\Requests\MailingAddressRequest;

class MailingAddressController extends Controller
{
    protected $mailingAddressInterface;

    public function __construct(MailingAddressInterface $mailingAddressInterface)
    {
        $this->mailingAddressInterface = $mailingAddressInterface;
    }

    public function addEmail(MailingAddressRequest $request)
    {
        $this->mailingAddressInterface->addEmail($request);
    }
}
