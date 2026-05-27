<?php
declare (strict_types = 1);

namespace app\service;

use app\model\User as DBUser;
use app\model\UserInfo as DBUserInfo;
use app\common\HttpCode;
use app\common\JwtUtil;
use \think\facade\Db;

class UserService
{
    /**
     * 登录用户并返回 token
     */
    public function loginByPhone(string $phone): array
    {
        $user = DBUser::where('phone', $phone)->find();

        if (!$user) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('error.user_not_found')
            ];
        }
        
        $token = JwtUtil::createToken([
            'phone' => $phone,
            'id' => $user->id,
            'user_code' => $user->user_code,
        ]);

        return [
            'code' => HttpCode::SUCCESS,
            'data' => $token,
            'msg' => lang('success.login')
        ];
    }

    /**
     * 注册用户并返回 token
     */
    public function registerByPhone(string $phone): array
    {
        try {
            $userId = DBUser::insertGetId([
                'phone' => $phone,
                'last_login_ip' => get_client_ip(),
                'last_login_time' => now(),
            ]);
        } catch (\Throwable $th) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('error.db')
            ];
        }

        $token = JwtUtil::createToken([
            'phone' => $phone,
            'id' => $userId,
            'user_code' => $user->user_code,
        ]);

        return [
            'code' => HttpCode::SUCCESS,
            'data' => $token,
            'msg' => lang('success.register')
        ];
    }

    /**
     * 根据用户 ID 获取用户信息
     */
    public function getUserById(int $id): ?DBUser
    {
        return DBUser::find($id);
    }

    /**
     * 获取用户代码
     */
    private function getUserCode(): string
    {
        // 先转字符串，再截取最后6位（毫秒级时间戳）
        $timeStr = (string)(int)(microtime(true) * 1000);
        $time = substr($timeStr, -6);
        // 4位随机数
        $rand = mt_rand(1000, 9999);
        // 拼接 10 位数字
        $code = $time . $rand;
        // 检查数据库是否重复（重复则重新生成）
        $exists = DBUser::where('user_code', $code)->find();
        if ($exists) {
            return $this->getUserCode();
        }
        return $code;
    }


    /**
     * 模拟插入用户数据
     */
    public static function insertTestUser(): void
    {

        /* 测试用户数据 */
        $testData = [
            [
                'user_code' => (new self())->getUserCode(),
                'phone' => '18200000001',
                'last_login_ip' => '127.0.0.1',
                'last_login_time' => now(),
            ]
        ];

        foreach ($testData as $data) {
            if (!DBUser::where('phone', $data['phone'])->find()) {
                
                $userId = DBUser::insertGetId($data);

                DBUserInfo::create([
                    'user_id' => $userId,
                    'nickname' => '测试用户',
                    'level' => 1,
                    'integral' => 0,
                    'balance' => 0.00,
                    'sex' => 1,
                ]);
            }
        }

    }
}
