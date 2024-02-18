<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', Rule::in(['administrator', 'customer'])],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => [Rule::requiredIf($request->input('role') != 'customer'), 'confirmed', Rules\Password::defaults()],
            'latitude' => [Rule::requiredIf($request->input('role') != 'customer'), 'numeric'],
            'longitude' => [Rule::requiredIf($request->input('role') != 'customer'), 'numeric'],
        ]);

        $user = User::create([
            'name' => ucwords($request->name),
            'surname' => ucwords($request->surname),
            'email' => strtolower($request->email),
            'role' => $request->role ?? 'customer',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'password' => Hash::make($request->password ?? 'password'),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return response()->json([
            'token' => $user->createToken('auth')->plainTextToken
        ], 201);
    }
}
