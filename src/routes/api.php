<?php


use Illuminate\Routing\Router;

Route::prefix('v1')->middleware(['api', 'cors-should'])->namespace("App\Http\Controllers\Api\V1")->group(function (Router $router) {


    //$router->post('importLang', 'TestController@importLang');
    //$router->post('updateLang', 'TestController@updateLang');
    //$router->get('getLangList', 'TestController@getLangList');


    $router->post('device', 'PublicController@initDevice');
    $router->post('deviceLog', 'PublicController@deviceLog');

    $router->get('switchLanguage', 'PublicController@switchLanguage');

    $router->get('in18n', 'InitController@in18n');

    $router->get('init', 'InitController@init');

    $router->get("getName", "AuthController@getName");

    $router->post("login", "AuthController@login");

    $router->post("register", "AuthController@register");

    $router->post("sendRegisterSms", "AuthController@sendRegisterSms");

    $router->get("home", "HomeController@home");

    $router->get("shareInfo", "PublicController@shareInfo");
    $router->get("annunciation", "PublicController@annunciation");

    $router->get("articles", "ArticleController@getList");

    $router->post("getArticle", "ArticleController@getArticle");

    $router->get("taskList", "TaskController@taskList");
    $router->get("getTaskInfo", "TaskController@getTaskInfo");

    $router->get("products", "ProductController@products");
    $router->get("product", "ProductController@product");

    //充值
    $router->get("rechargeBegin", "RechargeController@begin");

    $router->middleware('auth:api')->group(function (Router $router) {

        $router->get("testNtf", "TestController@testNtf");

        $router->get("user", "UserController@info");

        $router->post("changePassword", "UserController@changePassword");
        $router->post("changeName", "UserController@changeName");

        $router->post("setInvite", "UserController@setInvite");

        $router->post("signIn", "UserController@signIn");
        $router->get("signInInfo", "UserController@signInInfo");

        $router->get("friend", "UserController@friend");
        $router->get("friendList", "UserController@friendList");
        $router->get("friendAward", "UserController@friendAward");


        $router->get("unreadNotificationsCount", "UserController@unreadNotificationsCount");
        $router->get("notifications", "UserController@notifications");
        $router->post("notificationRead", "UserController@notificationRead");

        $router->get("getYesterdayProfit", "UserController@getYesterdayProfit");


        //钱包
        $router->post("walletTransform", "WalletController@transform");
        $router->post("walletLogs", "WalletController@walletLogs");


        //赚钱宝
        $router->post("depositMoneyBao", "MoneyBaoController@depositMoneyBao");
        $router->post("takeOutMoneyBao", "MoneyBaoController@takeOutMoneyBao");
        $router->post("receiveMoneyBaoAward", "MoneyBaoController@receiveMoneyBaoAward");

        //投资
        $router->post("buyProduct", "ProductController@buyProduct");
        $router->get("userProducts", "ProductController@userProducts");

        //充值
        $router->post("putInInOnlineOrder", "RechargeController@putInInOnlineOrder");
        $router->get("usdtAddress", "RechargeController@getUsdtAddress");
        $router->post("putInTransferVoucher", "RechargeController@putInTransferVoucher");
        $router->get("transferVoucherList", "RechargeController@transferVoucherList");
        $router->get("userRechargeOrderList", "RechargeController@userRechargeOrderList");
        $router->get("getUserRechargeOrder", "RechargeController@getUserRechargeOrder");


        //提现
        $router->get("withdrawBegin", "WithdrawController@begin");
        $router->post("putInWithdraw", "WithdrawController@putInWithdraw");
        $router->get("withdrawList", "WithdrawController@withdrawList");
        $router->get("getDeductInfo", "WithdrawController@getDeductInfo");


    });


    $router->post('paytmCash', 'CallBackController@paytmCash');
    $router->post('paytmCashPayOutBack', 'CallBackController@paytmCashPayOutBack');
    $router->post('laoSun', 'CallBackController@laoSun');
    $router->post('IPayIndianPayIn', 'CallBackController@IPayIndianPayIn');
    $router->post('IPayIndianPayOut', 'CallBackController@IPayIndianPayOut');
    $router->post('fPayCallBack', 'CallBackController@fPayCallBack');
    $router->post('yudrsuPayInBack', 'CallBackController@yudrsuPayInBack')->name('yudrsuPayInBack');
    $router->post('yudrsuPayOutBack', 'CallBackController@yudrsuPayOutBack')->name('yudrsuPayOutBack');
    $router->post('jstPayInBack', 'CallBackController@jstPayInBack')->name('jstPayInBack');
    $router->post('jstPayOutBack', 'CallBackController@jstPayOutBack')->name('jstPayOutBack');
    $router->post('usdt1788PayInBack', 'CallBackController@usdt1788PayInBack')->name('usdt1788PayInBack');
    $router->post('usdt1788PayOutBack', 'CallBackController@usdt1788PayOutBack')->name('usdt1788PayOutBack');

});




