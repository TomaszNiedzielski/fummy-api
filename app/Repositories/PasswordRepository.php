<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\PasswordInterface;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use DB;

class PasswordRepository implements PasswordInterface
{
    public function sendResetLink(Request $request) {
        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->exists();
        
        if(!$emailExists) {
            return (object) ['code' => 404, 'message' => 'Nie znaleziono użytkownika z podanym adresem e-mail.'];
        }

        $keysFromLastDay = DB::table('password_reset_keys')
            ->where('email', $request->email)
            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 days')))
            ->get();

        if(count($keysFromLastDay) >= 3) {
            return (object) ['code' => 429, 'message' => 'Limit wysłanych linków do resetu hasła został wykorzystany. Spróbuj ponownie jutro.'];
        }

        $key = $this->createPasswordResetKey($request->email);

        Mail::to($request->email)->send(new PasswordResetMail($key));

        return (object) ['code' => 200, 'message' => 'E-mail z linkiem do resetu hasła został wysłany na podany adres.'];
    }

    private function createPasswordResetKey(string $email) {
        $key = Str::random(40);

        DB::table('password_reset_keys')
            ->insert([
                'email' => $email,
                'value' => $key,
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+14 days'))
            ]);

        return $key;
    }

    public function reset(Request $request) {
        $key = DB::table('password_reset_keys')
            ->where('value', $request->key)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        if(!isset($key)) {
            return (object) ['code' => 410, 'message' => 'Ten link wygasł lub token jest niepoprawny.'];
        }

        DB::table('users')
            ->where('email', $key->email)
            ->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT)
            ]);

        return (object) ['code' => 200, 'message' => 'Hasło zostało zmienione.'];
    }

    public function update(Request $request) {
        $hash = User::find(auth()->user()->id)->password;

        if(! Hash::check($request->currentPassword, $hash)){
            return (object) [
                'code' => 400,
                'errors' => (object) [
                    'currentPassword' => 'Podane hasło jest nieprawidłowe.'
                ]
            ];
        }

        User::find(auth()->user()->id)->update([
            'password' => password_hash($request->newPassword, PASSWORD_DEFAULT)
        ]);

        return (object) ['code' => 200, 'message' => 'Hasło zostało zaaktualizowane.'];
    }
}