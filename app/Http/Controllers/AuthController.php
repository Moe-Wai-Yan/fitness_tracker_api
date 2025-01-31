<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(){
      $validator=Validator::make(request()->all(),[
        'name'=>'required|min:3|max:50',
        'email'=>'required|email|unique:users,email',
        'password'=>'required',
        'phone'=>'required|min:8'
      ]);
      if ($validator->fails()) {
        return response()->json([
            'status'=>false,
            'message'=>'unprocessable data',
            'errors'=>$validator->errors()
        ],422);
      }


      $user=User::create([
        'name'=>request('name'),
        'email'=>request('email'),
        'phone'=>request('phone'),
        'password'=>Hash::make(request('password')),
        'body_weight'=>request('body_weight'),
        'height'=>request('height'),
        'bio'=>request('bio'),
      ]);


    // Create a token for the user
    $token = $user->createToken('token_name')->plainTextToken;


      return response()->json([
        'status'=>true,
        'message'=>'register successful',
        'token'=>$token
      ],201);

    }

    public function login(){
$validator=Validator::make(request()->all(),[
    'email'=>'required|email',
    'password'=>'required',
        ]);

        if ($validator->fails()) {
           return response()->json([
            'status'=>false,
            'message'=>'unprocessable data',
            'errors'=>$validator->errors()
           ],422);
        }
        if (!auth()->attempt(request(['email','password']))) {
            return response()->json([
                'message'=>'Unauthorized',
                'status'=>false,
                'errors'=>[
                    'email' => ['These credentials do not match our records.']
                ]
                ],401);
        }
        $user=User::where('email',request('email'))->first();
        $token=$user->createToken('token_name')->plainTextToken;

        return response()->json([
            'message'=>'login successful',
            'status'=>true,
            'data'=>[
                'token'=>$token,
                'user'=>$user
            ]
        ],200);
    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return response()->json([],204);
    }


}
