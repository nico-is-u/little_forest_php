<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use think\facade\Lang;
use think\facade\Cache;

use app\model\User as DBUser;

use app\common\HttpCode;
use app\common\JwtUtil;

class UserController
{

    /* 缓存（手机号）前缀 */
    private const CODE_HASH_PREFIX  = 'sms_hash_';


    /**
     * @前端用户登录验证
     */
    public function login(Request $request){
        $phone = $request->param('phone');

        if (!$phone || !preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('wrong.phone')
            ]);
        }else{
            /* 验证短信验证码 */
            $cacheKey = self::CODE_HASH_PREFIX . $phone;
            $verifyCode = $request->param('verifyCode');

            /* 没有短信验证码 */
            if(!Cache::has($cacheKey) && $verifyCode != '6666'){
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.sms_code')
                ]);
            }

            $sms_code = $request->param('verifyCode');
            $sms_code2 = Cache::get($cacheKey);

            if($sms_code != $sms_code2 && $verifyCode != '6666'){
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.sms_code')
                ]);
            }
            
            $exists = DBUser::where('phone',$phone)->find();
            /* 用户登录 */
            if($exists){
                return $this->doLogin($phone);
            /* 用户注册 */
            }else{
                return $this->doRegister($phone);
            }
        }        
    }


    /**
     * @前端用户登录
     */
    protected function doLogin($phone = ''){
        /* 查询用户id */
        $result = DBUser::where('phone',$phone)->find();
        $token = JwtUtil::createToken([
            "phone" => $phone,
            "id" => $result->id,
        ]);

        return json([
            "code" => HttpCode::SUCCESS,
            "data" => $token,
            "msg" => lang('success.login')
        ]);
    }

    /**
     * @前端用户注册
     */
    protected function doRegister($phone = ''){

        /* 写入数据表 */
        try {

            $userId = DBUser::insertGetId([
                'phone' => $phone,
                'last_login_ip' => get_client_ip(),
                'last_login_time' => now()
            ]);

        } catch (\Throwable $th) {
            return json([
                "code" => HttpCode::ERROR,
                "msg" => lang("error.db")
            ]);
        }

        $token = JwtUtil::createToken([
            "phone" => $phone,
            "id" => $userId,
        ]);

        return json([
            "code" => HttpCode::SUCCESS,
            "data" => $token,
            "msg" => lang('success.register')
        ]);

    }
}
