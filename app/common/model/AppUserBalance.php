<?php
/**
 * 用户余额模型
 */
namespace app\common\model;

use think\model\concern\SoftDelete;

class AppUserBalance extends BaseModel
{
    use SoftDelete;

    protected $name = 'app_user_balance';

    protected $autoWriteTimestamp = true;

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo(AppUsers::class, 'user_id', 'id');
    }

    /**
     * 关联消费记录表
     */
    public function transactions()
    {
        return $this->hasMany(AppUserTransaction::class, 'user_id', 'user_id');
    }
}
