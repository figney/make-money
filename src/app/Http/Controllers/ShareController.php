<?php


namespace App\Http\Controllers;


use App\Models\Share;
use App\Services\AppService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{

    public function share(Request $request)
    {


        $params = $request->getRequestUri();
        $params = str_replace("/?", "?", $params);
        $params = str_replace("&hash=", "#/", $params);

        $local = $request->input('lang');
        $local = AppService::make()->local($local);

        $web_url = Str::finish(Setting('web_url'), '/');

        $data = [];

        $appShareInfo = Share::query()->inRandomOrder()->first();

        $data['app_id'] = '';
        $data['url'] = url()->current() . $request->getRequestUri();
        $data['site_name'] = '';
        $data['title'] = data_get($appShareInfo->title, $local);
        $data['description'] = data_get($appShareInfo->describe, $local);
        $data['image_url'] = data_get($appShareInfo->cover, $local);
        $data['go_url'] = $web_url . $params;



        return view('share', $data);
    }
}
