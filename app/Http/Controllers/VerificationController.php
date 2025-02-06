<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class VerificationController extends Controller
{
    public function verifyCode(Request $request)
    {
        try {
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required|numeric|digits:6', // Ensure it's a 6-digit number
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $email = $request->email;
            $code = $request->code;

            // Find the verification code in the database
            $verificationCode = VerificationCode::where('email', $email)
                ->where('code', $code)
                ->first();

            if (!$verificationCode) {
                return response()->json(['error' => 'Invalid verification code.'], 400);
            }

            // Check if the verification code has expired
            if ($verificationCode->expires_at < now()) {
                return response()->json(['error' => 'Verification code has expired.'], 400);
            }

            // Find the user and mark them as verified
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Update the user to set `is_verified` to true
            $user->is_verified = true;
            $user->save();

            // Delete the used verification code
            $verificationCode->delete();

            // Generate a JWT token
            $token = JWTAuth::fromUser($user);

         // Return the JWT token in the response, sent as a cookie
         return response()->json([
            'message' => 'User verified successfully.',
        ])->cookie('token', $token, 60); // Token expires in 60 minutes

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
