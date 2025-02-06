<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;  


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Extract email and password
            $email = $request->email;
            $password = $request->password;

            // Extract name from email (everything before "@")
            $name = explode('@', $email)[0];

            // Hash the password
            $hashedPassword = Hash::make($password);

            // Create new user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'is_verified' => false
            ]);

            // Generate a 6-digit verification code
            $verificationCode = mt_rand(100000, 999999);

            // Set expiration time (e.g., 10 minutes from now)
            $expiresAt = now()->addMinutes(10);

            // Store verification code in the database
            VerificationCode::create([
                'email' => $email,
                'code' => $verificationCode,
                'expires_at' => $expiresAt,
            ]);

                        // Send verification email
            Mail::to($email)->send(new VerificationMail($verificationCode));

            // Return JSON response indicating success
            return response()->json([
                'message' => 'User registered successfully. Please check your email for the verification code.'
            ], 201);


        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //login function
    public function login (Request $request) {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Extract email and password
            $email = $request->email;
            $password = $request->password;

            // Find the user by email
            $user = User::where('email', $email)->first();

            // Check if the user exists
            if (!$user) {
                return response()->json([
                    'error' => 'User not found.'
                ], 404);
            }

            // Check if the password is correct
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'error' => 'Invalid password.'
                ], 401);
            }

            // Check if the user is verified
            if (!$user->is_verified) {
                return response()->json([
                    'error' => 'User not verified.'
                ], 403);
            }

            // Generate a new API token
            $token = JWTAuth::fromUser($user);

            // Return the JWT token in the response, sent as a cookie
         return response()->json([
            'message' => 'User login sucessfully.',
        ])->cookie('token', $token, 60); // Token expires in 60 minutes

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    //logout function
    public function logout(Request $request) {
        try {
            // Invalidate the token
            JWTAuth::invalidate(JWTAuth::getToken());

            // Clear the cookie by setting it to null
            return response()->json([
                'message' => 'User logged out successfully.'
            ])->cookie('token', null, -1); // Set the cookie to expire in the past (delete it)

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


}
