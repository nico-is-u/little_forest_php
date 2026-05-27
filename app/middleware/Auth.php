<?php
declare (strict_types = 1);

namespace app\middleware;

use think\Request;
use think\Response;

use think\facade\Lang;

use app\common\HttpCode;
use app\common\JwtUtil;

/**
 * 认证中间件 - 捕获并验证 Authorization header 中的 Bearer token
 * 支持白名单机制，白名单中的接口不需要认证
 */
class Auth
{
    /**
     * 白名单接口列表 - 这些接口不需要 token 认证
     * 格式: ['请求方法' => '路由路径']
     */
    protected $whiteList = [
        'POST' => ['users/login'],
        'GET' => [
            'test',
            'get/sms_code',
            'get/communities'
        ],
    ];

    /**
     * 处理请求
     */
    public function handle(Request $request, \Closure $next): Response
    {
        // 检查当前请求是否在白名单中
        if ($this->isWhitelisted($request)) {
            return $next($request);
        }

        // 获取 Authorization header
        $authHeader = $request->header('Authorization');

        // 检查 header 是否存在
        if (!$authHeader) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.401')
            ], 401);
        }

        // 检查是否是 Bearer 格式
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.401')
            ], 401);
        }

        // 提取 token（去掉 "Bearer " 前缀）
        $token = substr($authHeader, 7);

        // 验证 token
        $decoded = JwtUtil::checkToken($token);
        if (!$decoded) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.401')
            ], 401);
        }

        // 将解析后的用户信息存储到 Request 对象中，供后续业务代码使用
        $request->authUser = $decoded;

        return $next($request);
    }

    /**
     * 检查当前请求是否在白名单中
     * 
     * @param Request $request
     * @return bool
     */
    protected function isWhitelisted(Request $request): bool
    {

        $method = $request->method();
        $path = $request->pathinfo();

        // 获取白名单中对应请求方法的路由列表
        $whiteListRoutes = $this->whiteList[$method] ?? [];

        // 检查当前路由是否在白名单中
        // 因为路由是在 api/v1 组下，这里只需要比较组内的路由路径
        foreach ($whiteListRoutes as $route) {
            // path 格式类似 "api/v1/users/login"，白名单中是 "users/login"
            if (str_ends_with($path, $route) || $path === $route) {
                return true;
            }
        }

        return false;
    }
}

