<?php
/**
 * @获取客户端ip
 */
function get_client_ip()
{
    $request = \think\facade\Request::instance();

    if ($ip = $request->header('x-forwarded-for')) {
        $arr = explode(',', $ip);
        $ip  = trim($arr[0]);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    if ($ip = $request->header('x-real-ip')) {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
    }

    return $request->ip();
}
/**
 * @获取当前时间
 */
function now(){
    return date('Y-m-d H:i:s');
}