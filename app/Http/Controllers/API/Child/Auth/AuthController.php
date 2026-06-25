<?php

namespace App\Http\Controllers\API\Child\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $validator = validator($request->all(), [
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 422);
    }

   
$token = auth()->guard('child')->attempt($request->only('email', 'password'));
    if (!$token) {
      return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return $this->respondWithToken($token);
  }

  public function me()
  {
    $child = auth()->guard('child')->user();
   return response()->json([
        'id' => $child->id,
        'name' => $child->name,
        'pairing_code' => $child->pairing_code, // ده الكود اللي هنظهره في الموبايل
        'is_active' => $child->is_active
    ]);
  }

  public function logout()
  {
    auth('child')->logout();
    return response()->json(['message' => 'Logged out']);
  }

  private function respondWithToken($token)
  {
    $child = auth()->guard('child')->user();
    return response()->json([
      'access_token' => $token,
      'type' => 'Bearer',
      'expires_in' => auth('child')->factory()->getTTL() * 60,
      'user' => [
            'id' => $child->id,
            'name' => $child->name,
            'pairing_code' => $child->pairing_code, // الكود اللي هنعرضه
        ]
    ]);
  }
}
