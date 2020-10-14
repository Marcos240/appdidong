<?php

namespace App\Http\Middleware;

use App\Item;
use Closure;

class CheckItemExistenceMiddleware
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
        // check if this Item exists?
        $item = Item::find($req->route('id'));
        
        if (!$item) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        // attach Item to req
        $req->request->add(['item' => $item]);
        
        return $next($req);
    }
}
