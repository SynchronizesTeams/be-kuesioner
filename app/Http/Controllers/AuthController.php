<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validate = $request->validate([
            'nis' => 'required|integer'
        ]);

        $user = User::where('nis', $request->nis)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|integer',
            'kelas' => 'required|string'
        ]);

        $user = null;
        DB::transaction(function () use ($request, &$user) {
            $user_id = uniqid('siswa_');
            $user = User::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'nis' => $request->nis,
                'kelas' => $request->kelas
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout success'
        ]);
    }
}
