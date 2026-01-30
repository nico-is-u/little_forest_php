<?php
declare (strict_types = 1);

namespace app\api\controller\v1;
use app\common\model\Config as ConfigModel;

use think\Request;

class App
{
    public function info(){
        $db = new ConfigModel();
        $configList = $db::getConfigsByGroup('app', true);

        return json([
            'code' => 1,
            'data' => $configList
        ]);
        
    }
}
