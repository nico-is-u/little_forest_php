<?php
declare (strict_types = 1);

namespace app\service;

use app\model\User as DBUser;
use app\common\HttpCode;
use app\common\JwtUtil;

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
}
