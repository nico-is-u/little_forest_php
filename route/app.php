<?php
use think\facade\Route;

/**
 * @API接口组
 */
Route::group('api/v1', function () {

    /* 路由测试 */
    Route::get('test', function () {return 'api test';});


    /**
     * @用户模块
     */
    Route::group('users',function(){
        /* @用户登录 */
        Route::post('login', 'UserController/login');
    });


    /**
     * @公共接口
     */
    Route::group('get', function () {
        /* @获取验证码 */
        Route::get('sms_code', 'OtherController/sms_code');
    });

})
->namespace('app\controller\api');
