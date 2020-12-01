<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Response;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdatePasscodeRequest;
use App\Utils\UserModificationHanlder;
use Validator;


class AuthController extends Controller {

    public function signup(SignupRequest $req) {

        // create a new record of User
        $user = null;

        // handle request body to store user's infomations
        $body = $req->all();
        $filter = ['username', 'email', 'name', 'passcode', 'passcodeCofirm', 'phone'];

        $newUser = UserModificationHanlder::saveUser($user, $body, $filter);
        
        // Response cookie
        return $this->responseCookie($newUser, 201);
    }

    public function login(LoginRequest $req) {
        // Get email, passcode for login
        ['username' => $username, 'passcode' => $passcode] = $req->input();

        // Get user information includes passcode.
        $user = User::fields()->where('username', $username)->addSelect('passcode')->first();
        
        // Check user still exists?
        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Người dùng này không tồn tại'
            ], 404);
        }

        // Select additionally passcode as well
        $user->makeVisible(['passcode']);


        // Check passcode
        if (!Hash::check($passcode, $user->passcode)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Username hoặc mật khẩu không hợp lệ'
            ], 401);
        }

        // Hide user passcode
        $user->makeHidden(['passcode']);
        

        // Response cookie
        return $this->responseCookie($user, 200);
    }

    public function logout(Request $req) {
        return $this->responseCookie(null, 200);
    }

    public function isLoggedIn(Request $req) {
        $user = $req->user;

        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
        
    }

    public function updatePasscode(UpdatePasscodeRequest $req) {
        // Get required fields for updating passcode from req body
        [
            "currentPasscode" => $currentPasscode, 
            "passcode" => $passcode, 
            "passcodeConfirm" => $passcodeConfirm
        ] = $req->input(); 

        // Check passcode
        $user = $req->user;
        
        if (!Hash::check($currentPasscode, $user->passcode)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Mật khẩu không chính xác'
            ], 401);
        }

        // OK -> continue updating passcode
        // Response success message
        return $this->responseCookie($user, 200);
    }

    private function signToken($id) {
        // Create factory includes required fields in payload
        $factory = JWTFactory::customClaims([
            'sub' => $id ? $id : '_logoutsecretkey',
            'iss' => env('JWT_SECRET'),
            'exp' => $id ? Carbon::now()->timestamp + env('JWT_TTL') * 86400 : Carbon::now()->timestamp + 2
            ]);
        // Create payload
        $payload = $factory->make();
        // Create and return token includes payload
        $token = (string) JWTAuth::encode($payload);
        return $token;
    }

    private function responseCookie($user, $statusCode) {
        // Create jwt token
        $token = $user ?  $this->signToken($user->id) : $this->signToken(null);

        // Create cookie
        $cookie = Cookie::make(
            'jwt', 
            $token, 
            !$user ? 2 : env('JWT_COOKIES_EXPIRES_IN') * 864000,
            '/',
            null, 
            null, 
            env('APP_ENV') === 'production' ? true : false
        );

        // Return json response includes cookies
        return Response::json([
            'status' => 'success',
            'token' => $token,
            'data' => $user
        ], $statusCode)->withCookie($cookie);
    }
}
