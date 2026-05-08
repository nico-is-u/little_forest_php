<?php
namespace app\common;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtil
{
    // 生成 Token
    public static function createToken($user)
    {
        $config = config('jwt');
        $payload = [
            'iat' => time(),
            'exp' => time() + $config['expire'],
            'user_id' => $user['id'],
            'phone' => $user['phone'],
        ];
        return JWT::encode($payload, $config['secret'], 'HS256');
    }

    // 验证 Token
    public static function checkToken($token)
    {
        try {
            $config = config('jwt');
            $decoded = JWT::decode($token, new Key($config['secret'], 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}