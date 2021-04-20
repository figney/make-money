<?php


namespace App\Http\Controllers;


use App\Enums\OrderStatusType;
use App\Models\Recharge;
use App\Models\User;

use App\Models\UserRechargeOrder;
use App\Models\Xapi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class XapiController extends Controller
{

    public function index(Request $request)
    {

        $minute=(int)$request->get( 'minute' );//分钟数
        $token=$request->get( 'token' );//token
        if($minute==0){
            $minute=20;
        }
        //用户注册======================================================
        $time = [Carbon::now()->addMinutes(-$minute), Carbon::now()];
        $user_count = User::query()->whereBetween('created_at', $time)->count();

        $minute_last=$minute*2;
        $time_last = [Carbon::now()->addMinutes(-$minute_last), Carbon::now()->addMinutes(-$minute)];
        $user_count_last= User::query()->whereBetween('created_at', $time_last)->count();

        $user_info=array(
            "user_count"=>$user_count,
            "user_count_last"=>$user_count_last,
        );

        //订单======================================================
        $recharge_count= UserRechargeOrder::query()->whereBetween('created_at', $time)->count();
        $recharge_success_count= UserRechargeOrder::query()->where('order_status', OrderStatusType::PaySuccess)->whereBetween('created_at', $time)->count();

        $recharge_info=array(
            "recharge_count"=>$recharge_count,
            "recharge_success_count"=>$recharge_success_count,
        );

        $data=array(
            "user_info"=>$user_info,
            "recharge_info"=>$recharge_info,
        );

        return response()->json(array(
            "code" => 200,
            "message" => "success",
            "data" => $data,
        ));
    }
}
