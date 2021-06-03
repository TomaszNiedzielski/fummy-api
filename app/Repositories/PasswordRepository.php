<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Interfaces\PasswordInterface;
use Illuminate\Support\Str;
use App\Models\PasswordResetKey;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use DB;

class PasswordRepository implements PasswordInterface
{
    public function sendResetLink(Request $request) {
        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->exists();
        
        if(!$emailExists) {
            return (object) ['status' => 'error', 'message' => 'Nie znaleziono użytkownika z podanym adresem e-mail.'];
        }

        $keysFromLastDay = DB::table('password_reset_keys')
            ->where('email', $request->email)
            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 days')))
            ->get();

        if(count($keysFromLastDay) >= 3) {
            return (object) ['status' => 'error', 'message' => 'Limit wysłanych linków do resetu hasła został wykorzystany. Spróbuj ponownie jutro.'];
        }

        $key = $this->createPasswordResetKey($request->email);

        Mail::to($request->email)->send(new PasswordReset($key));

        return (object) ['status' => 'success', 'message' => 'E-mail z linkiem do resetu hasła został wysłany na podany adres.'];
    }

    private function createPasswordResetKey(string $email) {
        $key = new PasswordResetKey();
        $key->email = $email;
        $key->value = Str::random(40);
        $key->created_at = date('Y-m-d H:i:s');
        $key->expires_at = date('Y-m-d H:i:s', strtotime('+14 days'));
        $key->save();

        return $key->value;
    }

    public function change(Request $request) {
        $key = DB::table('password_reset_keys')
            ->where('value', $request->key)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if(!isset($key)) {
            return (object) ['status' => 'error', 'message' => 'Ten link wygasł lub token jest niepoprawny.'];
        }

        $updatedRecords = DB::table('users')
            ->where('email', $key->email)
            ->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT)
            ]);

        if($updatedRecords === 0) {
            return (object) ['status' => 'error', 'message' => 'Coś poszło nie tak.'];
        }

        return (object) ['status' => 'success', 'message' => 'Hasło zostało zmienione.'];
    }
}