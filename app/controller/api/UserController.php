<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;

class UserController
{
    /**
     * @前端用户登录
     */
    public function login(Request $request){
        $phone = $request->param('phone');
        if (!$phone || !preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => '手机号码格式不正确'
            ]);
        }

        
    }
}
