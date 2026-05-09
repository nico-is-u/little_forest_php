<?php
use think\facade\Route;

/**
 * @API接口组
 */
Route::group('api/v1', function () {

    /* 路由测试 */
    Route::get('test', 'OtherController/only_test');


    /**
     * @用户模块
     */
    Route::group('users',function(){
        /* @用户登录 - 白名单 */
        Route::post('login', 'UserController/login');

        /* @获取用户信息 - 需要认证 */
        Route::get('info', 'UserController/info');
    });


    /**
     * @公共接口
     */
    Route::group('get', function () {
        /* @获取验证码 - 白名单 */
        Route::get('sms_code', 'OtherController/sms_code');
    });

})
->namespace('app\controller\api')
->middleware(\app\middleware\Auth::class);
