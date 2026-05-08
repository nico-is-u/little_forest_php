<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use think\facade\Cache;
use think\facade\Lang;
use think\middleware\annotation\RateLimit;

use app\common\HttpCode;

class OtherController
{

    /* 缓存（手机号）前缀 */
    private const CODE_HASH_PREFIX  = 'sms_hash_';

    /**
     * 拉取短信验证码
     */
    // #[RateLimit(rate: "2/m")]
    public function sms_code(Request $request)
    {
        /* 验证手机号码 */
        $phone = $request->param('phone');
        if (!$phone || !preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('wrong.phone')
            ]);
        }

        /* 60秒内限流只能拉取一次验证码,如果允许则返回哈希给前端，验证不在这里做 */    
        $cacheKey = self::CODE_HASH_PREFIX . $phone;
        
        /**
         * 生成短信验证码，调用三方API
         */

        $sms_code = str_pad((string)random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        /* 验证码存储60秒，过期后自动删除 */
        Cache::set($cacheKey, $sms_code, 60);

        /* 这里应该调用三方API发送短信验证码，成功后才返回成功响应 */
        /* 这里为了演示直接返回成功响应，并将验证码存储在缓存中，实际项目中请勿将验证码返回给前端 */
        
        return json([
            'code' => HttpCode::SUCCESS,
            'msg' => lang('success.sms_code'),
            'data' => [
                'sms_code' => $sms_code // 仅用于测试，实际项目中请勿将验证码返回给前端
            ]
        ]);
        

    }

}
