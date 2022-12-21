<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use stdClass;
use App\Http\Requests\AuthRequest;

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
                'message_one' => 'Hi ' . $user->name,
                'accessToken' => $token,
                'token_type' => 'bearer',
                'user' => $user,
            ]);
    }

    //Crud de usuarios
    //Mostrar
    public function show()
    {
        $user = User::all();
        return response()
            ->json(['user' => $user]);
    }

    //Actualizar
    public function edit(Request $request)
    {
        $user = User::find($request->id);
        $user->password = hash::make($request->password);
        $user->save();
        return [
            'message' => 'password update',
            'user' => $user
        ];
    }

    //Eliminar
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()
            ->json(['message' => 'User delete']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
            return ['message' => 'session logout'];
        }
        return ['message' => 'authenticate fail'];
    }
}
