<?php


namespace App\Http\Controllers\Api\V1;


use App\Enums\LanguageConfigType;
use App\Http\Controllers\Api\ApiController;
use App\Models\Language;
use App\Models\LanguageConfig;
use App\Models\Notifications\RechargeOrderSuccessNotification;
use App\Models\Notifications\TestNotification;
use App\Models\Notifications\TransferVoucherPassNotification;
use App\Models\Notifications\TransferVoucherRejectNotification;
use App\Models\Notifications\UserAwardNotification;
use App\Models\Notifications\UserDeductAwardNotification;
use App\Models\Notifications\UserEarningsNotification;
use App\Models\Notifications\UserFriendDeductAwardNotification;
use App\Models\Notifications\UserProductCommissionNotification;
use App\Models\Notifications\UserProductCommissionV2Notification;
use App\Models\Notifications\UserProductOverNotification;
use App\Models\Notifications\UserWithdrawRefundNotification;
use App\Models\Notifications\UserWithdrawRejectNotification;
use App\Models\Notifications\UserWithdrawToPayErrorNotification;
use App\Models\Notifications\UserWithdrawToPayNotification;
use App\Models\Notifications\UserYesterdayProfitNotification;
use App\Models\Task;
use App\Models\UserProduct;
use App\Models\UserRechargeOrder;
use App\Models\UserTransferVoucher;
use App\Models\UserWithdrawOrder;
use App\Models\WalletLog;
use App\Services\LangService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TestController extends ApiController
{


    /**
     * 更新语言-gxyyyy
     * @queryParam slug   required 标识
     * @queryParam content   required 内容
     * @group 测试-test
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updateLang(Request $request)
    {
        try {
            $this->validatorData($request->all(), [
                'slug' => 'required',
                'content' => 'required',
            ]);

            $slug = $request->input('slug');
            $content = $request->input('content');

            $slug = Str::upper($slug);
            $slug = str_replace("-", "_", $slug);


            LanguageConfig::query()->where('slug', $slug)->update(['content->VI' => $content]);


            return $this->responseMessage('success');
        } catch (\Exception $exception) {
            return $this->responseException($exception);
        }

    }

    /**
     * 获取语言-hqyy
     * @queryParam local  required 语言包
     * @group 测试-test
     * @param Request $request
     * @return array
     */
    public function getLangList(Request $request)
    {


        $list = LanguageConfig::query()->pluck('content', 'slug');

        $local = $request->input('local', 'CN');

        $list = collect($list)->map(function ($item) use ($local) {
            return data_get($item, $local);
        })->all();

        return $list;
    }

    /**
     * 导入语言-dryy
     * @queryParam name   required 中文名称
     * @queryParam slug   required 标识
     * @queryParam group   required 分组
     * @group 测试-test
     * @param Request $request
     */
    public function importLang(Request $request)
    {
        try {

            $this->validatorData($request->all(), [
                'name' => 'required',
                'slug' => 'required',
                'group' => 'required',
            ]);

            $name = $request->input('name');
            $slug = $request->input('slug');
            $group = $request->input('group', 'client');

            $slug = Str::upper($slug);
            $slug = str_replace("-", "_", $slug);

            abort_if(Str::containsAll($slug, ["{", "}"]), 400, "语言标识错误，不能带{ }");

            $langContent = $name;

            foreach (Language::query()->get() as $lang) {
                $content[$lang->slug] = $langContent;
            }
            LanguageConfig::query()->firstOrCreate(['slug' => $slug], [
                'type' => LanguageConfigType::client,
                'name' => $name,
                'content' => $content,
                'group' => $group
            ]);

            return $this->responseMessage('success');

        } catch (\Exception $exception) {
            return $this->responseException($exception);
        }


    }


    /**
     * 消息发送测试-xxfs测试
     * @queryParam type   required 消息类型
     * @group 测试-test
     * @authenticated
     * @param Request $request
     */
    public function testNtf(Request $request)
    {

        $user = $this->user();


        $type = $request->input('type');

        switch ($type) {
            case "RechargeOrderSuccessNotification":
                $user->notify(new RechargeOrderSuccessNotification(UserRechargeOrder::query()->inRandomOrder()->first()));
                break;
            case "TransferVoucherRejectNotification":
                $user->notify(new TransferVoucherRejectNotification(UserTransferVoucher::query()->first()));
                break;
            case "TransferVoucherPassNotification":
                $user->notify(new TransferVoucherPassNotification(UserTransferVoucher::query()->first()));
                break;
            case "UserAwardNotification":
                $user->notify(new UserAwardNotification(100, "测试通知奖励", Task::query()->inRandomOrder()->first()));
                break;
            case "UserDeductAwardNotification":
                $user->notify(new UserDeductAwardNotification(UserWithdrawOrder::query()->first(), 100));
                break;
            case "UserEarningsNotification":
                $user->notify(new UserEarningsNotification(WalletLog::query()->first(), $user));
                break;
            case "UserFriendDeductAwardNotification":
                $user->notify(new UserFriendDeductAwardNotification(UserWithdrawOrder::query()->first(), 50));
                break;
            case "UserWithdrawRefundNotification":
                $user->notify(new UserWithdrawRefundNotification(UserWithdrawOrder::query()->first()));
                break;
            case "UserWithdrawRejectNotification":
                $user->notify(new UserWithdrawRejectNotification(UserWithdrawOrder::query()->first(), $user->local));
                break;
            case "UserWithdrawToPayErrorNotification":
                $user->notify(new UserWithdrawToPayErrorNotification(UserWithdrawOrder::query()->first(), $user->local));
                break;
            case "UserWithdrawToPayNotification":
                $user->notify(new UserWithdrawToPayNotification(UserWithdrawOrder::query()->first()));
                break;
            case "UserProductCommissionNotification":
                $user->notify(new UserProductCommissionNotification(100, 1, UserProduct::query()->first()));
                break;
            case "UserProductOverNotification":
                $user->notify(new UserProductOverNotification(UserProduct::query()->first()));
                break;
            case "UserYesterdayProfitNotification":
                $user->notify(new UserYesterdayProfitNotification(100, 300));
                break;
            case "UserProductCommissionV2Notification":

                $up = UserProduct::query()->latest()->first();

                $type = 1;

                if ($type == 0) $user->notify(new UserProductCommissionV2Notification(0, 100, true, false, $up->day_cycle > 100, 1, $up->user, $up, 0, 0));

                if ($type == 1) $user->notify(new UserProductCommissionV2Notification(50, 100, false, false, $up->day_cycle > 100, 1, $up->user, $up, 50000, 20000));


                break;
        }

        return $user->id;


    }


}
