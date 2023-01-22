<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use stdClass;
use App\Http\Requests\AuthRequest;
use App\Mail\MailController;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token');

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauhtorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'message' => 'Hi ' . $user->name,
                'accessToken' => $token,
                'token_type' => 'bearer',
                'user' => $user,
            ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
            return ['message' => 'session logout'];
        }
        return ['message' => 'authenticate fail']; //duda no muestra este mensaje
    }

    public function passwordRecovery(Request $request)
    {
        if ($user = User::find($request->id)) { //no permite la busqueda a traves de email, retorna null
            $token = $user->createToken('auth_token')->plainTextToken;

            $mail = new MailController($token, $user);
            Mail::to($user->email)->send($mail);

            return response()
                ->json([
                    'message' => 'Send message',
                ]);
        }
        return [
            'message' => 'user not found',
        ];
    }
}
