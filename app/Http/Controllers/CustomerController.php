<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Models\CustomerView;
use Illuminate\Support\Facades\Crypt;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'customers' => new UserCollection(User::where('role', 'customer')->get())
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::find(Crypt::decrypt($id));

        if (!$user) return response()->json([
            'message' => 'User not found'
        ], 404);

        return response()->json([
            'customer' => new UserResource($user)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $decryptedId = Crypt::decrypt($id);
        $user = User::find($decryptedId);

        if (!$user) return response()->json([
            "message" => "User not found"
        ], 404);

        $this->validate($request, [
            'name' => ['sometimes', 'string'],
            'surname' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->user_id . ',user_id'],
            'longitude' => ['sometimes', 'numeric'],
            'latitude' => ['sometimes', 'numeric'],
        ]);

        $attributes = $request->input();
        $updateAttributes = [];

        foreach ($attributes as $key => $attribute) {
            if ($user[$key] !== $attribute) $updateAttributes[$key] = $attribute;
        }

        $user->update($updateAttributes);

        return response()->json([
            'customer' => new UserResource($user)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
