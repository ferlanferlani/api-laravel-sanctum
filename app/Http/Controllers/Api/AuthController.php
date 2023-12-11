<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{


    // api register function   
    public function register(Request $registerUserRequest)
    {
        try {

            $dataRegisterValidated = $registerUserRequest->validate([
                'username' => 'required|min:3',
                'email' => 'required|email',
                'password' => 'required|min:5',
                'confirm_password' => 'required|same:password',
                'profile_picture_name' => 'image|mimes:jpg,jpeg,png|max:1024',
            ]);
            $nameProfilePictureDefault = '';

            $exitingUsername = User::where('username', $dataRegisterValidated['username'])->first();

            if ($exitingUsername) {
                return response()->json([
                    'sucess' => false,
                    'message' => 'this username is already exists'
                ]);
            }

            $baseUrl = config('app.url');

            if ($registerUserRequest->hasFile('profile_picture_name')) {
                $profilePicturePath = $dataRegisterValidated['profile_picture_name'] = $registerUserRequest->file('profile_picture_name')->store('images/profile_picture', 'public');
                $UrlProfilePictureFull = url($baseUrl . '/' . $profilePicturePath);
                $profilePictureName = basename($profilePicturePath);
            } else {
                $nameProfilePictureDefault = 'profile_picture_default.jpg';
                $profilePictureName = $nameProfilePictureDefault;
                $UrlProfilePictureFull = url($baseUrl . '/' . 'images/profile_picture/' . $profilePictureName);
            }

            $dataRegisterValidated['password'] = bcrypt($dataRegisterValidated['password']);

            $dataUserRegistrationSuccessfully = User::create([
                'username' => $dataRegisterValidated['username'],
                'email' => $dataRegisterValidated['email'],
                'password' => $dataRegisterValidated['password'],
                'profile_picture_name' => $profilePictureName,
                'profile_picture_url' => $UrlProfilePictureFull
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration Successfully',
                $dataUserRegistrationSuccessfully
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'Success' => false,
                'Error Message' => $e->getMessage(),
            ], 500);
        }
    }

    // api login function
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            $user = Auth::User();
            $userLoginToken = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'succeed' => true,
                'message' => 'Login successfuly',
                'data User' => $user,
                'auth_token' => $userLoginToken,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'username or password incorrect'
            ]);
        }
    }

    // api logout function
    public function logout(Request $request)
    {
    }
}
