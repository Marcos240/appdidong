<?php

namespace App\Http\Controllers;

use App\Item;
use App\LikedUser;
use App\ChosenItem;
use Illuminate\Http\Request;
use App\Http\Requests\ItemModificationRequest;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\User;
use App\Utils\ItemModificationHandler;
use Illuminate\Support\Facades\Auth;
use DB;


class ItemController extends Controller {

    public function getListItems(){
        $item = Item::paginate(20);
        if($item->currentPage() <= $item->lastPage())
            return response()->json([
                'status' => 'success',
                'data' => $item
            ], 201);
        else 
            return response()->json([
                'status' => 'fail'
            ], 404);
            

    }

    public function getItem(Request $req) {

        // get Item from previous middleware 
        $item = $req->input('item');
        return response()->json([
            'status' => 'success',
            'data' => $item
        ], 200);
    }


    

    public function updateLikeItem(Request $req) {

        if ($req->has('like')) {
            $data = LikedUser::where('idUser',$req->user->id)->where('idItem',$req->input('item')->id)->get();
            if( $req->like == 1 && $data->count() == 0)
            {
                DB::table('items')->where('id',$req->input('item')->id)->update(['liked'=> ($req->input('item')->liked) +1 ]);
                DB::table('liked_users')->insert(
                    [ 'id' => null,
                    'idUser' => $req->user->id,
                    'idItem'=> $req->input('item')->id]
                );
                return response()->json([
                    'status' => 'success',
                ]);
            }
            if( $req->like == -1 && $data->count() != 0)
            {
                DB::table('items')->where('id',$req->input('item')->id)->update(['liked'=> ($req->input('item')->liked) - 1 ]);
                DB::table('liked_users')->where('idUser',$req->user->id)->where('idItem',$req->input('item')->id)->delete();
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',
        ]);
    }

    public function chosenItem(Request $req){
        $data = ChosenItem::where('idUser',$req->user->id)->where('idItem',$req->input('item')->id)->get();
        if($data->count() == 0 && $req->has('count') && $req->count > 0)
        {
            DB::table('chosen_items')->insert(
                [ 'id' => null,
                'idUser' => $req->user->id,
                'idItem'=> $req->input('item')->id,
                'count'=> $req->count]
            );
            return response()->json([
                'status' => 'success',
            ]);
        }

        foreach ($data as $data) {
            $count = $data->count;
        }

        if($data->count() != 0 && $req->has('count') && $req->count > -($count))
        {
            
            DB::table('chosen_items')->where('idItem',$req->input('item')->id)->where('idUser',$req->user->id)->update(['count'=> $count + $req->count]);
            return response()->json([
                'status' => 'success',
            ]);
        }
        if($data->count() != 0 && $req->has('count') && $req->count <= -$count)
        {
            DB::table('chosen_items')->where('idItem',$req->input('item')->id)->where('idUser',$req->user->id)->delete();
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'fail',
        ]);
    }

    public function getChosenItem(Request $req){
        // check if this Item exists?
        $chosen = ChosenItem::where('idUser',$req->user->id);
        if (!$chosen) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Giỏ hàng không tồn tại'
            ], 404);
        }
        else
        $result = DB::table('chosen_items')->join('items','chosen_items.idItem','=','items.id')->select('items.id','items.name','items.cost','items.avatar')->get();
        return response()->json([
            'status' => 'success',
            'data' => $result,
        ], 201);

    }

    private static function filterUser($srcUser) {
        $user = (object)[];
        $user->id = $srcUser->id;
        $user->name = $srcUser->name;
        $user->photo = $srcUser->photo;
        return $user;
    }
}
