<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Item;
use App\LikedUser;
use App\ChosenItem;
use App\Bill;
use App\DetailBill;
use App\Http\Requests\ItemModificationRequest;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\User;
use App\Utils\ItemModificationHandler;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class BillController extends Controller
{
    public function createBill(Request $req){
        $chosen = ChosenItem::where('idUser',$req->user->id)->get();
        if (count($chosen) <= 0) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Giỏ hàng không tồn tại'
            ], 404);
        }
        else
        {
            DB::table('bills')->insert(
                [ 'id' => null,
                'idUser' => $req->user->id,
                'dateOrder' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                'addressShipping'=> $req->addressShipping ,
                'message'=> $req->message]
            );
            $idBill = Bill::where('idUser',$req->user->id)->where('dateOrder',Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString())->select('id')->get();
            foreach($idBill as $idBill)
            {
                $id = $idBill->id;
            }
            $resultItem = DB::table('chosen_items')->join('items','chosen_items.idItem','=','items.id')->select('items.id','items.name','items.cost','items.avatar','chosen_items.count')->get();

            foreach ($resultItem as $resultItem)
            {
                $y = $resultItem->id;
                $z = $resultItem->count;
                DB::table('detail_bills')->insert(
                    [ 'id' => null,
                    'idBill' => $id,
                    'idItem' => $y, 
                    'counting' => $z,
                    ]
                );
            }
            DB::table('users')->where('id',$req->user->id)->update(['pointCollected'=> ($req->user->pointCollected)+($req->totalCost)/1000]);
            DB::table('users')->where('id',$req->user->id)->update(['pointUsable'=> ($req->user->pointUsable)+($req->totalCost)/1000]);
            DB::table('chosen_items')->where('idUser',$req->user->id)->delete();
            $bill = Bill::where('id', $id)->get();
            return response()->json([
                'status' => 'success',
                'data' => $bill,
            ], 201);
        }
    }
    public function getBill(Request $req){
        $bill = Bill::where('idUser',$req->user->id)->orderByDesc('dateOrder')->get();
        $i = 0;
        foreach($bill as $bill)
        {
            $data =  DB::table('detail_bills')->join('items','detail_bills.idItem','=','items.id')->select('items.name','items.cost','detail_bills.counting','detail_bills.idBill');
            $result[$i] = $bill;
            $result[$i]['detail_bills'] = $data->where('idBill',$bill->id)->get();
            $i++;
        }
        return response()->json([
            'status' => 'success',
            'data' => $result
        ], 201);
    }
}
