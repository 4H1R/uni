<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::insert('insert into users
                    (name, email, email_verified_at, password, is_admin, remember_token, created_at, updated_at)
                    VALUE (?, ?, null, ?, false,null, now(), now())',
            [$request->name, $request->email, Hash::make($request->password)]
        );

        $user = User::find(DB::getPdo()->lastInsertId());

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
