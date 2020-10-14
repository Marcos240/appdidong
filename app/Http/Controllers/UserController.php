<?php

namespace App\Http\Controllers;


use App\Http\Requests\UpdateUserProfileRequest;
use App\Utils\ImageHandler;
use App\Utils\UserModificationHanlder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * * get a particular user's profile infomation
     */
    public function getUserProfile(Request $req) {
        // get user from previous middleware
        $user = $req->user;

        return $this->responseWithUser($user);
    }

    public function updateUserProfile(UpdateUserProfileRequest $req) {
        
        // get user from previous middleware
        $user = $req->user;

        // get passcodeConfirm from request
        $passcodeConfirm = $req->get('passcodeConfirm');

        // check if passcode confirm is correct?
        if (!Hash::check($passcodeConfirm, $user->passcode)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Mật khẩu không chính xác'
            ], 401);
        };

        // filter for allowed fields (except for photo, address)!
        $filter = ['name'];
        $body = $req->all();

        // save user
        $savedUser = UserModificationHanlder::saveUser($user, $body, $filter);

        return $this->responseWithUser($savedUser);
    }

    private function responseWithUser($user) {
        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
