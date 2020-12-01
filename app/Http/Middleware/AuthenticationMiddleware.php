<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        // There existing a cookie ?
        if (!$req->cookie('jwt')) {
            // User have not logged in yet
            return response()->json([
                'status' => 'fail',
                'message' => 'You haven\'t logged in yet! Please log in to continue'
            ]);
        }
        // Get token from cookie
        $token = $req->cookie('jwt');
            
        // Decode token
        try {
            $decoded = JWTAuth::setToken($token)->getPayload();
            $decoded = json_decode($decoded);
            // Check if token has expired, based on time expire from payload in decoded token
        } catch (TokenExpiredException $e) { 
            return response()->json([
                'status' => 'fail',
                'message' => 'The token is invalid or has expired!'
            ], 401);
        }

        // Get user includes additionally passcode, passcodeChangedAt property based on id from payload in decoded token
        $id = $decoded->sub;
        $user = User::fields()->addSelect(['passcode', 'passcodeChangeAt'])->find($id);

        // Get the time when token got issued (from decoded token) to compare with passcodeChangedAt?
        $iat = $decoded->iat;
        if (Carbon::parse($user->passcodeChangeAt)->timestamp > $iat) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Bạn đã thay đổi mật khẩu gần đây. Vui lòng đăng nhập lại với mật khẩu mới!'
            ]);
        }


        // Check if user still exists
        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This user is not available'
            ], 404);
        }

        // Attach user to req
        $req->request->add(['user' => $user]);
        return $next($req);
    }
}
