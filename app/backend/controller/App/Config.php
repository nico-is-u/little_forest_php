<?php
/**
 * App配置管理
 */
namespace app\backend\controller\App;

use app\common\controller\Backend;
use app\common\model\Config as ConfigModel;
use think\facade\Cache;
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
            
            // 使用模型批量更新配置
            $db = new ConfigModel();
            $result = $db::updateConfigs($post, 'app');
            
            if ($result === false) {
                $this->error('保存失败');
            }

            Cache::clear();
            $this->success('保存成功');
        }

        // 使用模型获取app分组下的所有配置
        $db = new ConfigModel();
        $configList = $db::getConfigsByGroup('app', true);

        View::assign('formData', $configList);
        return view();
    }
}
