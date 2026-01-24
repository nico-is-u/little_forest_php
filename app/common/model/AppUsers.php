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

    /**
     * 关联用户余额表
     */
    public function balance()
    {
        return $this->hasOne(AppUserBalance::class, 'user_id', 'id');
    }
}
