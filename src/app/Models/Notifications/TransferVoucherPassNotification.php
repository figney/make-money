<?php


namespace App\Models\Notifications;


use App\Http\Resources\UserTransferVoucherResource;
use App\Models\UserTransferVoucher;

/**
 * 转账订单审核通过通知
 * @package App\Models\Notifications
 */
class TransferVoucherPassNotification extends BaseNotification implements INotification
{


    public bool $forced = false;
    public bool $socket = true;

    public $title_slug = "转账订单审核完成通知";
    public $content_slug = "转账订单审核通过内容";

    public $type = "TransferVoucherPassNotification";

    protected $userTransferVoucher;

    public function __construct(UserTransferVoucher $userTransferVoucher)
    {
        $this->userTransferVoucher = $userTransferVoucher;
    }


    public function toArray(): array
    {

        return json_decode(UserTransferVoucherResource::make($this->userTransferVoucher)->toJson(), true);//转账订单对象
    }

    public function toParams(): array
    {
        return [];
    }
}
