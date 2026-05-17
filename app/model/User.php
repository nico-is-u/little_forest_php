<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use app\model\UserInfo;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    protected $name = 'users';

    public function profile()
    {
        return $this->hasOne(UserInfo::class,'user_id');
    }

    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class, 'user_id');
    }
}
