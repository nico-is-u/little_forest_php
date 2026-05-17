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
        Route::post('info', 'UserController/edit');

    });


    /**
     * @优惠券模块
     */
    Route::group('coupons', function(){
        /* @获取用户优惠券列表 - 需要认证 */
        Route::get('list', 'CouponController/list');

        /* @领取优惠券 - 需要认证 */
        Route::post('receive', 'CouponController/receive');

        /* @验证优惠券 - 需要认证 */
        Route::post('validate', 'CouponController/validate');

        /* @使用优惠券 - 需要认证 */
        Route::post('use', 'CouponController/use');
    });


    /**
     * @公共接口
     */
    Route::group('get', function () {
        /* @获取验证码 - 白名单 */
        Route::get('sms_code', 'OtherController/sms_code');
    });

    Route::group('common', function () {
        /* @上传文件 - 需要认证 */
        Route::post('upload', 'OtherController/upload');
    });

})
->namespace('app\controller\api')
->middleware(\app\middleware\Auth::class);
