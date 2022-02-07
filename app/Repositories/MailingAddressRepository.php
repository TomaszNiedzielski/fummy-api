<?php

namespace App\Repositories;

use App\Interfaces\MailingAddressInterface;
use App\Http\Requests\MailingAddressRequest;
use App\Models\MailingAddress;

class MailingAddressRepository implements MailingAddressInterface
{
    public function addEmail(MailingAddressRequest $request) {
        MailingAddress::create([
            'email' => $request->email
        ]);
    }
}