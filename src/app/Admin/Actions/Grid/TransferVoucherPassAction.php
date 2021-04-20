<?php

namespace App\Admin\Actions\Grid;

use App\Enums\RechargeChannelType;
use App\Enums\TransferVoucherCheckType;
use App\Enums\WalletLogType;
use App\Enums\WalletType;
use App\Models\Notifications\TransferVoucherPassNotification;
use App\Models\UserTransferVoucher;
use App\Models\UserTransferVoucherCheckLog;
use App\Models\Wallet;
use App\Models\WalletLog;
use App\Services\RechargeService;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class TransferVoucherPassAction extends RowAction
{

    protected $title = '审核通过';


    public function handle(Request $request)
    {

        try {
            $id = $this->getKey();
            $userTransferVoucher = UserTransferVoucher::whereCheckType(TransferVoucherCheckType::UnderReview)->whereStatus(false)->findOrFail($id);
            $action_type = WalletLogType::DepositTransferVoucherRecharge;
            $order = $userTransferVoucher->userRechargeOrder;

            $order->remark_slug = "审核通过";
            $order->back_time = now();


            RechargeService::make()->rechargeOrderSuccess($order, $action_type, function (Wallet $wallet, WalletLog $walletLog) use ($userTransferVoucher) {

                $userTransferVoucher->check_type = TransferVoucherCheckType::Pass;
                $userTransferVoucher->check_slug = "审核通过";
                $userTransferVoucher->status = 1;
                $userTransferVoucher->check_time = now();
                $userTransferVoucher->wallet_log_id = $walletLog->id;
                $userTransferVoucher->save();

            });
            //写入审核记录
            UserTransferVoucherCheckLog::query()->create([
                'user_transfer_voucher_id' => $userTransferVoucher->id,
                'admin_user_id' => \Admin::user()->id,
                'check_type' => $userTransferVoucher->check_type,
                'check_slug' => $userTransferVoucher->check_slug,
            ]);
            //发送消息通知
            //$user->notify(new TransferVoucherPassNotification($userTransferVoucher));

            return $this->response()->success("操作成功")->refresh();

        } catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage())->alert();
        }


        return $this->response()->success('成功！')->refresh();
    }

    public function confirm()
    {
        return ['你确定要通过当前转账信息吗？', view('admin.TransferVoucherPassAction', $this->row)->render()];
    }

}
