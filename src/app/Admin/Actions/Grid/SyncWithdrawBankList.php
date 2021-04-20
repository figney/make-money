<?php

namespace App\Admin\Actions\Grid;

use App\Enums\PlatformType;
use App\Models\RechargeChannel;
use App\Models\RechargeChannelList;
use App\Models\WithdrawChannel;
use App\Models\WithdrawChannelList;
use App\Services\Pay\FPayTHBService;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SyncWithdrawBankList extends RowAction
{

    protected $title = '同步银行列表';


    public function handle(Request $request)
    {
        $id = (int)$this->getKey();

        $wc = WithdrawChannel::query()->find($id);


        if ($wc->slug == PlatformType::FPay) {
            $list = FPayTHBService::make()->withConfigWithdraw($wc)->withdraw_bank_list();

            foreach ($list as $item) {
                WithdrawChannelList::query()->firstOrCreate(
                    [
                        'bank_code' => $item['id'],
                        'withdraw_channel_id' => $wc->id

                    ],
                    [
                        'bank_name' => $item['bank_name'],
                        'name' => $item['bank_name'],
                        'input_config' => []
                    ]
                );
            }
            return $this->response()->message('更新成功')->refresh();
        }
        if ($wc->slug === PlatformType::JstPay) {
            $arr = [
                1548 => 'VIB',
                1549 => 'VPBank',
                2001 => 'BIDV',
                2002 => 'VietinBank',
                2003 => 'SHB',
                2004 => 'ABBANK',
                2005 => 'AGRIBANK',
                2006 => 'Vietcombank',
                2007 => 'Techcom',
                2008 => 'ACB',
                2009 => 'SCB',
                2011 => 'MBBANK',
                2012 => 'EIB',
                2020 => 'STB',
                2031 => 'DongABank',
                2032 => 'GPBank',
                2033 => 'Saigonbank',
                2034 => 'PG Bank',
                2035 => 'Oceanbank',
                2036 => 'NamABank',
                2037 => 'TPB',
                2038 => 'HDB',
                2039 => 'VAB',
            ];
            foreach ($arr as $key => $item) {
                WithdrawChannelList::query()->firstOrCreate(
                    [
                        'bank_code' => $key,
                        'withdraw_channel_id' => $wc->id

                    ],
                    [
                        'bank_name' => $item,
                        'name' => $item,
                        'min_money' => 2000000,
                        'max_money' => 200000000,
                        'input_config' => [
                            [
                                'name' => 'bank_account',
                                'slug' => 'BANK_ACCOUNT',
                                'desc' => '收款人开户姓名',
                            ],
                            [
                                'name' => 'bank_no',
                                'slug' => 'BANK_NO',
                                'desc' => '收款人银行帐号',
                            ]
                        ]
                    ]
                );
            }
            return $this->response()->message('更新成功')->refresh();
        }
        if ($wc->slug === PlatformType::Yudrsu) {
            $json = '[{"code": "ACEH", "name": "Bank Pembangunan Daerah Istimewa Aceh", "status": 1}, {"code": "ARTHA", "name": "Bank Artha Graha Internasional", "status": 1}, {"code": "BALI", "name": "BPD Bali", "status": 1}, {"code": "BCA", "name": "Bank BCA", "status": 1}, {"code": "BCA_SYR", "name": "Bank BCA Syariah", "status": 1}, {"code": "BENGKULU", "name": "BPD Bengkulu", "status": 1}, {"code": "BJB", "name": "Bank Jabar Banten", "status": 1}, {"code": "BJB_SYR", "name": "Bank Jabar Banten Syariah", "status": 1}, {"code": "BNI", "name": "Bank BNI", "status": 1}, {"code": "BNI_SYR", "name": "Bank BNI Syariah", "status": 1}, {"code": "BOC", "name": "Bank of China", "status": 1}, {"code": "BRI", "name": "Bank BRI", "status": 1}, {"code": "BRI_SYR", "name": "Bank BRI Syariah", "status": 1}, {"code": "BTN", "name": "Bank Tabungan Negara (Persero)", "status": 1}, {"code": "BTN_UUS", "name": "Bank BTN Syariah", "status": 1}, {"code": "BTPN", "name": "Bank BTPN", "status": 1}, {"code": "BTPN_SYARIAH", "name": "Bank BTPN Syariah", "status": 1}, {"code": "BUKOPIN", "name": "Bank Bukopin", "status": 1}, {"code": "BUMI_ARTA", "name": "Bank Bumi Arta", "status": 1}, {"code": "CHINATRUST", "name": "Bank Chinatrust Indonesia", "status": 1}, {"code": "CIMB", "name": "Bank CIMB Niaga", "status": 1}, {"code": "CITIBANK", "name": "Citibank", "status": 1}, {"code": "COMMONWEALTH", "name": "Bank Commonwealth", "status": 1}, {"code": "DANAMON", "name": "Bank Danamon Indonesia", "status": 1}, {"code": "DANAMON_SYR", "name": "Bank Danamon Syariah", "status": 1}, {"code": "DBS", "name": "Bank DBS Indonesia", "status": 1}, {"code": "DEUTSCHE", "name": "Bank Deutsche", "status": 1}, {"code": "DKI", "name": "Bank DKI Jakarta", "status": 1}, {"code": "DKI_UUS", "name": "Bank DKI Jakarta Syariah", "status": 1}, {"code": "GANESHA", "name": "Bank Ganesha", "status": 1}, {"code": "HANA", "name": "Bank Hana", "status": 1}, {"code": "HSBC", "name": "Bank HSBC Indonesia", "status": 1}, {"code": "ICBC", "name": "Bank ICBC Indonesia", "status": 1}, {"code": "JAMBI", "name": "BPD Jambi", "status": 1}, {"code": "JAWA_TENGAH", "name": "BPD Jawa Tengah", "status": 1}, {"code": "JAWA_TIMUR", "name": "BPD Jawa Timur (Jatim)", "status": 1}, {"code": "KALIMANTAN_BARAT", "name": "BPD Kalimantan Barat", "status": 1}, {"code": "KALIMANTAN_SELATAN", "name": "BPD Kalimantan Selatan", "status": 1}, {"code": "KALIMANTAN_TIMUR", "name": "BPD Kalimantan Timur", "status": 1}, {"code": "LAMPUNG", "name": "BPD Lampung", "status": 1}, {"code": "MALUKU", "name": "BPD Maluku", "status": 1}, {"code": "MANDIRI", "name": "Bank Mandiri", "status": 1}, {"code": "MANDIRI_SYR", "name": "Bank Mandiri Syariah", "status": 1}, {"code": "MASPION", "name": "Bank Maspion Indonesia", "status": 1}, {"code": "SINARMAS", "name": "Bank Sinarmas", "status": 1}]';
            $list = json_decode($json, true);

            abort_if(!is_array($list), 400, "银行列表对象解析失败");

            foreach ($list as $item) {
                WithdrawChannelList::query()->firstOrCreate(
                    [
                        'bank_code' => $item['code'],
                        'withdraw_channel_id' => $wc->id

                    ],
                    [
                        'bank_name' => $item['name'],
                        'name' => $item['name'],
                        'min_money' => 100000,
                        'max_money' => 50000000,
                        'input_config' => [
                            [
                                'name' => 'acc_no',
                                'slug' => 'ACC_NO',
                                'desc' => '收款账号',
                            ],
                            [
                                'name' => 'acc_name',
                                'slug' => 'ACC_NAME',
                                'desc' => '收款姓名',
                            ]
                        ]
                    ]
                );
            }
            return $this->response()->message('更新成功')->refresh();

        }
        return $this->response()->error('不支持');

    }

}
