<?php
/**
 * 测试用控制器
 */
namespace app\backend\controller\App;

use app\common\controller\Backend;

class Test extends Backend
{
    public function index(){
        $cosService = app('cos');
        return dump($cosService->getTempUrl('upload/0124/bd53aa611fa4c95a56d59d9f25f35ec5.jpg'));
    }
}
