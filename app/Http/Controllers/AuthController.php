<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Traits\ResponseAPI;
use App\Http\Requests\{RegisterRequest, LoginRequest};
use DB;

class AuthController extends Controller
{
    use ResponseAPI;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->error('Niepoprawny e-mail lub hasło.', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request) {
        $user = new User;
        $user->full_name = $request->fullName;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->nick = $request->nick;
        $user->save();

        $token = auth()->attempt(['email' => $user, 'password' => $request->password]);

        $data = (object) [
            'nick' => $user->nick,
            'token' => $token
        ];
    
        return $this->success($data, 'User successfully registered.');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'code' => 200,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Create nick from full name.
     * 
     * @param   string $fullName
     * 
     * @return  string $nick
     */
    private function createNick(string $fullName) : string
    {
        $nick = '';
        $fullNameExploded = explode(' ', $fullName);

        foreach($fullNameExploded as $i=>$value) {
            if($i < count($fullNameExploded)-1) {
                $nick = $nick.$value.'-';
            } else {
                $nick = $nick.$value;
            }
        }

        $nick = strtolower($nick);

        return $nick;
    }

    public function updatePassword(Request $request) {
        $hash = User::find(auth()->user()->id)->password;
        if(Hash::check($request->currentPassword, $hash)){
            DB::table('users')
                ->where('id', auth()->user()->id)
                ->update([
                    'password' => password_hash($request->newPassword, PASSWORD_DEFAULT)
                ]);

            return $this->success(null, 'Hasło zostało zaaktualizowane.');
        } else {
            return $this->error([
                'errors' => (object) [
                    'currentPassword' => 'Podane hasło jest nieprawidłowe.'
                ]
            ]);
        }
    }
}