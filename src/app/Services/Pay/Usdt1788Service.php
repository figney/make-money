<?php


namespace App\Services\Pay;


use App\Enums\OrderStatusType;
use App\Enums\WalletLogType;
use App\Enums\WithdrawOrderStatusType;
use App\Models\RechargeChannelList;
use App\Models\User;
use App\Models\UserRechargeOrder;
use App\Models\UserWithdrawOrder;
use App\Models\WithdrawChannel;
use App\Services\BaseService;
use App\Services\RechargeService;
use App\Services\WithdrawService;

class Usdt1788Service extends BaseService
{

    protected string $merchantId = "10068";

    protected string $key = "02bd0dfd75b3f6828c72a222a00ab8d5";

    protected string $currency = "VND";
    protected string $version = "v1.0";


    public function payIn(User $user, UserRechargeOrder $userRechargeOrder, ?RechargeChannelList $rechargeChannelList, string $redirect_url)
    {
        $notifyUrl = config('app.url') . route('usdt1788PayInBack', [], false);

        $faker = $this->faker();

        $data['merchantId'] = $this->merchantId;
        $data['tradeNo'] = $userRechargeOrder->order_sn;
        $data['paymentType'] = (int)$rechargeChannelList->bank_code;
        $data['amount'] = $userRechargeOrder->amount * 100;
        $data['currency'] = $this->currency;
        $data['callback'] = $redirect_url;
        $data['notify'] = $notifyUrl;
        $data['version'] = "v1.0";

        $data['sign'] = $this->sign($data);

        $data['name'] = $user->name;
        $data['email'] = $faker->email;
        $data['mobile'] = "84589024390";


        $url = "https://usdt1788.in/center/api/prepay.do";

        $res = \Http::asForm()->post($url, $data);
        abort_if($res->clientError(), $res->status(), "请求失败");


        $res_data = $res->json();

        $status = data_get($res_data, "code") == 0;
        $message = data_get($res_data, "msg");
        if (!$status) {

            $userRechargeOrder->delete();

            abort(400, $message);
        }


        return data_get($res_data, "data.url");


    }

    public function payInBack($data)
    {

        $data_arr = data_get($data, 'data');

        $order_sn = data_get($data_arr, 'tradeNo');
        $amount = (float)data_get($data_arr, 'amount', 0);
        $amount = $amount / 100;
        $sign = data_get($data_arr, 'sign');
        $platform_sn = data_get($data_arr, 'orderNo');
        $currency = data_get($data_arr, 'currency');
        $payStatus = data_get($data, 'code') == 0;

        abort_if(!$order_sn, 400, "order_sn error");
        abort_if($currency !== $this->currency, 400, "currency error");
        $order = UserRechargeOrder::query()->where('order_sn', $order_sn)->first();
        abort_if(!$order, 400, "The order does not exist");
        if ($order->order_status == OrderStatusType::PaySuccess) return;
        abort_if($order->order_status !== OrderStatusType::Paying, 400, "Order status error");


        $data_sign = $this->sign($data_arr);

        if ($data_sign === $sign) {
            $order->back_time = now();
            $order->platform_sn = $platform_sn;
            $order->actual_amount = (float)$amount;
            //修改金额，防止用户不按提交金额付款
            $order->amount = (float)$amount;
            if ($payStatus) {
                $message = data_get($data, "msg", "pay success");
                $order->remark = $message;
                RechargeService::make()->rechargeOrderSuccess($order, WalletLogType::DepositOnlinePayRecharge);
            } else {
                $message = data_get($data, "msg", "pay error");
                $order->remark = $message;
                RechargeService::make()->rechargeOrderError($order);
            }

        } else {
            abort(400, "Bad Signature");
        }

    }


    public function payOut(UserWithdrawOrder $userWithdrawOrder, WithdrawChannel $withdrawChannel)
    {
        $notifyUrl = config('app.url') . route('usdt1788PayOutBack', [], false);
        $faker = $this->faker();
        $data['merchantId'] = $this->merchantId;
        $data['tradeNo'] = $userWithdrawOrder->order_sn;
        $data['type'] = 1;
        $data['name'] = data_get($userWithdrawOrder->input_data, "name");
        $data['account'] = data_get($userWithdrawOrder->input_data, "bank_account");
        $data['bankCode'] = $userWithdrawOrder->withdrawChannelItem->bank_code;
        $data['email'] = $faker->email;
        $data['mobile'] = "84589024390";
        $data['amount'] = $userWithdrawOrder->actual_amount * 100;
        $data['currency'] = $this->currency;
        $data['version'] = "v1.0";
        $data['notify'] = $notifyUrl;

        $data['sign'] = $this->sign($data);
        $url = "https://usdt1788.in/center/api/payout.do";
        $res = \Http::asForm()->post($url, $data);
        abort_if($res->clientError(), $res->status(), "请求失败");

        $res_data = $res->json();

        $status = data_get($res_data, "code") == 0;
        $message = data_get($res_data, "msg");

        abort_if(!$status, 400, $message);

        $userWithdrawOrder->platform_sn = data_get($res_data, "data.orderNo");
        $userWithdrawOrder->order_status = WithdrawOrderStatusType::Paying;
        $userWithdrawOrder->save();

    }

    public function payOutBack($data)
    {

        $data_arr = data_get($data, 'data');
        $order_sn = data_get($data_arr, 'tradeNo');
        $currency = data_get($data_arr, 'currency');
        $sign = data_get($data_arr, 'sign');
        $payStatus = data_get($data, "code") == 0;
        abort_if(!$order_sn, 400, "order_sn error");
        abort_if($currency !== $this->currency, 400, "currency error");
        $userWithdrawOrder = UserWithdrawOrder::query()->where('order_sn', $order_sn)->first();
        abort_if(!$userWithdrawOrder, 400, "The order does not exist");
        if ($userWithdrawOrder->order_status == WithdrawOrderStatusType::CheckSuccess) return;
        abort_if($userWithdrawOrder->order_status !== WithdrawOrderStatusType::Paying, 400, "Order status error");
        $data_sign = $this->sign($data_arr);
        if ($data_sign === $sign) {
            $userWithdrawOrder->back_time = now();
            if ($payStatus) {
                WithdrawService::make()->withdrawOrderSuccess($userWithdrawOrder);
            } else {
                $userWithdrawOrder->remark = data_get($data, "err_msg", "pay error");
                WithdrawService::make()->withdrawOrderError($userWithdrawOrder);
            }

        } else {
            abort(400, "Bad Signature");
        }

    }

    private function sign($data): string
    {
        $signPars = "";
        ksort($data);
        foreach ($data as $k => $v) {
            if ("" != $v && "sign" != $k) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars .= "key=" . $this->key;

        return strtoupper(md5($signPars));//strtolower 小写  strtoupper大写

    }

}
