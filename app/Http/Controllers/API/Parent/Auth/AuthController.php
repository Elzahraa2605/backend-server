<?php

namespace App\Http\Controllers\API\Parent\Auth;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $validator = validator($request->all(), [
      'name' => ['required'],
      'email' => ['required', 'email', 'unique:parents'],
      'password' => ['required', 'confirmed', 'min:6'],
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $parent = Parents::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    $token = auth('parent')->login($parent);

    return $this->respondWithToken($token);
  }

  public function login(Request $request)
  {
    $validator = validator($request->all(), [
      'email' => ['required', 'string', 'email'],
      'password' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 422);
    }

    $token = auth('parent')->attempt($request->only('email', 'password'));

    if (!$token) {
      return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return $this->respondWithToken($token);
  }

  public function me()
  {
    return response()->json(auth('parent')->user());
  }

  public function logout()
  {
    auth('parent')->logout();
    return response()->json(['message' => 'Logged out']);
  }

  protected function respondWithToken($token)
  {
    // السطر ده عشان نتأكد إن الـ token اتولد فعلاً
    if (!$token) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    return response()->json([
      'access_token' => $token,
      'type' => 'Bearer',
      'expires_in' => auth('parent')->factory()->getTTL() * 60
    ]);
  }
}
