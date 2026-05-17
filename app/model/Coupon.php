<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Coupon extends Model
{
    protected $name = 'coupons';

    // 用户优惠券关系
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class, 'coupon_id');
    }
}