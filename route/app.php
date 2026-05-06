<?php
use think\facade\Route;

/**
 * @API接口组
 */
Route::group('api/v1', function () {

    /* 路由测试 */
    Route::get('test', function () {return 'api test';});



    /**
     * @公共接口
     */
    Route::group('get', function () {
        /* @获取验证码 */
        Route::get('sms_code', 'OtherController/sms_code');
    });

})->namespace('app\controller\api');
