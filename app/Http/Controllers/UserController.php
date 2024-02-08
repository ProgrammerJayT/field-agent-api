<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'users' => new UserCollection(User::all())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $newUser = User::create($request->validated());

            return response()->json([
                'user' => new UserResource($newUser)
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        try {
            $user->update($request->validated());

            return response()->json([
                'user' => new UserResource($user)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        try {
            return response()->json($user->delete(), 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
