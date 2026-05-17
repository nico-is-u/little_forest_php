<?php
return [
    'wrong' => [
        'phone' => "手机号码格式不正确",
        'sms_code' => "短信验证码错误",
        'params' => "参数错误",
        'upload_max' => "文件过大，最大支持 0.5MB",
    ],

    'success' => [
        'sms_code' => "短信验证码已发送",
        'login' => "登录成功",
        'logout' => '注销成功',
        'register' => "注册成功",
        'edit' => '更新成功',
    ],

    'error' => [
        'db' => "数据错误，请联系客服",
        'user_not_found' => "用户不存在",
        'portrait' => '头像上传失败， 请联系客服',
        '401' => "未授权访问，请提供有效的 token",
    ],

    'coupon' => [
        'not_found_or_expired' => '优惠券不存在或已过期',
        'out_of_stock' => '优惠券已领完',
        'already_received' => '您已领取过此优惠券',
        'receive_success' => '领取成功',
        'not_available' => '优惠券不可用',
        'expired' => '优惠券已过期',
        'min_amount_not_met' => '订单金额未达到优惠券使用门槛',
        'use_success' => '优惠券使用成功',
        'code_required' => '优惠券代码不能为空',
        'id_required' => '优惠券ID不能为空',
        'use_params_required' => '使用优惠券需要提供优惠券ID和订单ID',
    ],

    'user' => [
        ''
    ]
];