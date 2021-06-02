<?php

namespace App\Repositories;

use App\Interfaces\MailInterface;
use Illuminate\Http\Request;
use DB;
use App\Models\MailVerificationKey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verify;

class MailRepository implements MailInterface
{
    public function confirmVerification(Request $request) {
        $key = DB::table('mail_verification_keys')
            ->where('user_id', $request->userId)
            ->orderBy('created_at', 'desc')
            ->first();

        $exist = $key->value === $request->key;

        if($exist) {
            DB::table('users')
                ->where('id', $request->userId)
                ->update([
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);

            DB::table('mail_verification_keys')
                ->where('user_id', $request->userId)
                ->delete();
        }

        return $exist;
    }

    public function sendVerificationMail() {
        $user = auth()->user();

        if($user->email_verified_at) {
            return 'Twój adres email jest już zweryfikowany.';
        }

        $keysFromLastDay = DB::table('mail_verification_keys')
            ->where('user_id', $user->id)
            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 days')))
            ->get();

        if(count($keysFromLastDay) >= 3) {
            return 'Limit weryfikacyjnych wiadomości e-mail został wyczerpany. Spróbuj ponownie jutro.';
        }

        $key = $this->createVerificationKey($user->id);

        Mail::to($user->email)->send(new Verify($user->id, $user->nick, $key));

        return 'E-mail został wysłany.';
    }

    private function createVerificationKey(int $user_id) {
        $key = Str::random(40);
        $key = new MailVerificationKey();
        $key->user_id = $user_id;
        $key->value = Str::random(40);
        $key->expires_at = date('Y-m-d H:i:s', strtotime('+14 days'));
        $key->save();

        return $key->value;
    }
}