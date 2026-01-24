<?php
/**
 * 用户消费记录模型
 */
namespace app\common\model;

class AppUserTransaction extends BaseModel
{
    protected $name = 'app_user_transaction';

    protected $autoWriteTimestamp = true;

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->belongsTo(AppUsers::class, 'user_id', 'id');
    }

    /**
     * 关联用户余额表
     */
    public function balance()
    {
        return $this->belongsTo(AppUserBalance::class, 'user_id', 'user_id');
    }
}
