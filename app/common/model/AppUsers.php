<?php
/**
 * 小程序用户模型
 */
namespace app\common\model;

use think\model\concern\SoftDelete;

class AppUsers extends BaseModel
{
    use SoftDelete;

    protected $name = 'app_users';

    protected $autoWriteTimestamp = true;
}
