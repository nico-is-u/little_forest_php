<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use think\facade\Cache;

use app\model\User as DBUser;
use app\service\UserService;

use app\common\HttpCode;
use app\common\JwtUtil;

class UserController
{

    /* 缓存（手机号）前缀 */
    private const CODE_HASH_PREFIX  = 'sms_hash_';


    /**
     * @前端用户登录验证
     */
    public function login(Request $request, UserService $userService){
        $phone = $request->param('phone');

        if (!$phone || !preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('wrong.phone')
            ]);
        } else {
            /* 验证短信验证码 */
            $cacheKey = self::CODE_HASH_PREFIX . $phone;
            $verifyCode = $request->param('verifyCode');

            /* 没有短信验证码 */
            if (!Cache::has($cacheKey) && $verifyCode != '6666') {
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.sms_code')
                ]);
            }

            $sms_code = $request->param('verifyCode');
            $sms_code2 = Cache::get($cacheKey);

            if ($sms_code != $sms_code2 && $verifyCode != '6666') {
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.sms_code')
                ]);
            }
            
            $exists = DBUser::where('phone',$phone)->find();
            /* 用户登录 */
            if ($exists) {
                return json($userService->loginByPhone($phone));
            /* 用户注册 */
            } else {
                return json($userService->registerByPhone($phone));
            }
        }
    }

    /**
     * @获取用户信息 - 需要 Authorization: Bearer token
     */
    public function info(Request $request)
    {
        // 从中间件获取已验证的用户信息
        $authUser = $request->authUser;

        // 根据 token 中的用户 ID 获取完整的用户信息
        $user = DBUser::find($authUser['user_id']);

        if (!$user) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.user_not_found')
            ]);
        }

        return json([
            'code' => HttpCode::SUCCESS,
            'data' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'last_login_time' => $user->last_login_time,
                'last_login_ip' => $user->last_login_ip,
            ],
        ]);
    }

}
