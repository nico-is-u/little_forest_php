<?php
/**
 * App配置管理
 */
namespace app\backend\controller\App;

use app\common\controller\Backend;
use think\App as ThinkApp;
use think\facade\Cache;
use think\facade\Db;
use think\facade\View;
use app\common\annotation\ControllerAnnotation;
use app\common\annotation\NodeAnnotation;

/**
 * @ControllerAnnotation(title="App配置")
 * Class Config
 * @package app\backend\controller\App
 */
class Config extends Backend
{

    /**
     * @NodeAnnotation(title="系统设置")
     * @return \think\response\View
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            
            // 更新配置到数据库
            foreach ($post as $k => $v) {
                $res = Db::name('config')
                    ->where('code', $k)
                    ->where('group', 'app')
                    ->update(['value' => $v]);
            }

            Cache::clear();
            $this->success('保存成功');
        }

        // 获取app分组下的所有配置
        $configList = Db::name('config')
            ->where('group', 'app')
            ->where('status', 1)
            ->column('value', 'code');

        View::assign('formData', $configList);
        return view();
    }
}
