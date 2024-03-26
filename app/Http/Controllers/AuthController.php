<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function loginapi(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Generate token
        $token = bin2hex(random_bytes(32));

        // Save token to database or any storage mechanism you prefer
        DB::table('users')->where('id', $user->id)->update([
            'token' => $token
        ]);

        return response()->json(['token' => 'Bearer ' . $token], 200);

    }
}
