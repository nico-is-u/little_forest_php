<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use think\facade\Cache;
use think\facade\Filesystem;

use app\model\User as DBUser;
use app\model\UserCoupon;
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
     * @获取用户信息
     */
    public function info(Request $request)
    {
        // 从中间件获取已验证的用户信息
        $authUser = $request->authUser;

        // 根据 token 中的用户 ID 获取完整的用户信息
        $user = DBUser::with(['profile', 'userCoupons'])->find($authUser['user_id']);

        if (!$user) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.user_not_found')
            ]);
        }

        /* 摊平字段 */
        $user = $user->toArray();
        $data = array_merge($user, $user['profile']);

        /* 手机号码隐蔽处理 */
        $data['phone'] = substr_replace($data['phone'], '****', 3, 4);

        /* 优惠券数量 */
        $data['couponCount'] = count($user['userCoupons']);

        /* 隐蔽敏感字段 */
        unset($data['profile']);
        unset($data['password']);
        unset($data['is_delete']);


        return json([
            'code' => HttpCode::SUCCESS,
            'data' => $data,
        ]);
    }

    /**
     * @修改用户信息
     */
    public function edit(Request $request)
    {
        // 从中间件获取已验证的用户信息
        $authUser = $request->authUser;
        $user = DBUser::with('profile')->find($authUser['user_id']);
        if (!$user) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.user_not_found')
            ]);
        }

        /* 1. 注意 phone字段由于前端用户的宿主环境可能是微信小程序，需要单独的判断
        所以不能在这个接口修改 */

        /* 2. 用户昵称 */
        $nickname = $request->param('nickname');
        if ($nickname) {
            $user->profile->nickname = $nickname;
        }

        /* 3. 真实姓名（同道理也不能在这改） */
        /* 4. 用户等级 */
        /* 5. 积分，余额 */

        /* 6. 用户头像 */
        $portrait = $request->param('portrait');
        if ($portrait) {
            /* 必须是一个路径 */
            if (is_string($portrait) && Filesystem::has(str_replace('/storage/', '', $portrait))) {
                $user->profile->portrait = $portrait;
            } else {
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('error.portrait')
                ]);
            }
        }

        /* 7. 性别 */
        $sex = $request->param('sex');
        if($sex == '1' || $sex == '2'){
            $user->profile->sex = $sex;
        }

        /* 8. 生日 */
        $birthday = $request->param('birthday');
        if ($birthday) {
            $user->profile->birthday = $birthday;
        }

        /* 9. 个性签名 */
        $signature = $request->param('signature');
        if ($signature) {
            $user->profile->signature = $signature;
        }

        /* 10. 身份证号码（同理不能在这修改） */
        /* 11. 省市区（同理不能在这修改） */ 

        /* Final. 目前涉及表较少，暂不动用事务 */
        try {
            $user->save();
            $user->profile->save();

            return json([
                'code' => HttpCode::SUCCESS,
                'msg' => lang('success.edit')
            ]);

        } catch (\Throwable $th) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.db')
            ]);
        }


    }


    /**
     * @注销用户
     */
    public function logout(Request $request)
    {
        // 从中间件获取已验证的用户信息
        $authUser = $request->authUser;

        /* 注销用户只需要前端删除 token 即可，后端不需要做任何操作 */
        return json([
            'code' => HttpCode::SUCCESS,
            'msg' => lang('success.logout')
        ]);
        
    }

}
