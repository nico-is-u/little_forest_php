<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class UserCoupon extends Model
{
    protected $name = 'user_coupons';

    // 关联用户
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 关联优惠券
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}