<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Crud de usuarios
    //Mostrar
    public function show()
    {
        $user = User::all();
        return response()
            ->json(['user' => $user]);
    }

    //Actualizar
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8'
        ]);

        if ($user = User::find($request->id)) {
            $user->password = hash::make($request->password);
            $user->save();
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
            return [
                'message' => 'password update',
            ];
        }
        return [
            'message' => 'user not found'
        ];
    }

    public function updateUser(UserRequest $request)
    {
        if ($user = User::find($request->id)) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = hash::make($request->password);
            $user->save();
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
            return response()
                ->json([
                    'message' => 'user update',
                    'user' => $user,
                ]);
        }
        return [
            'message' => 'user not found',
        ];
    }

    //Eliminar
    public function destroy(Request $request)
    {
        if ($user = User::find($request->id)) {
            $user->delete();
            return response()
                ->json(['message' => 'User delete']);
        }
        return [
            'message' => 'user not found',
        ];
    }
}
