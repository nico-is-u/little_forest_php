<?php
declare (strict_types = 1);

namespace app\service;

use think\facade\Db;
use app\model\Coupon;
use app\model\UserCoupon;
use app\common\HttpCode;

class CouponService
{
    /**
     * 领取优惠券
     */
    public function receiveCoupon(int $userId, string $couponCode): array
    {
        $coupon = Coupon::where('coupon_code', $couponCode)
            ->where('valid_start_date', '<=', now())
            ->where('valid_end_date', '>=', now())
            ->where('status', 1)
            ->find();

        if (!$coupon) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.not_found_or_expired')
            ];
        }

        // 检查库存
        if ($coupon['total_count'] > 0 && $coupon['used_count'] >= $coupon['total_count']) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.out_of_stock')
            ];
        }

        // 检查用户是否已领取
        $exists = UserCoupon::where('user_id', $userId)
            ->where('coupon_id', $coupon['id'])
            ->find();

        if ($exists) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.already_received')
            ];
        }

        // 领取优惠券
        try {
            Db::startTrans();

            // 插入用户优惠券
            UserCoupon::create([
                'user_id' => $userId,
                'coupon_id' => $coupon['id'],
                'status' => 0, // 未使用
            ]);

            // 更新优惠券已使用数
            $coupon->inc('used_count')->update();

            Db::commit();

            return [
                'code' => HttpCode::SUCCESS,
                'msg' => lang('coupon.receive_success')
            ];
        } catch (\Throwable $th) {
            Db::rollback();
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('error.db')
            ];
        }
    }

    /**
     * 获取用户优惠券列表
     */
    public function getUserCoupons(int $userId, int $status = null): array
    {
        $query = UserCoupon::with('coupon')
            ->where('user_id', $userId);

        if ($status !== null) {
            $query->where('status', $status);
        }

        $coupons = $query->order('created_at', 'desc')->select();

        return [
            'code' => HttpCode::SUCCESS,
            'data' => $coupons
        ];
    }

    /**
     * 验证优惠券可用性
     */
    public function validateCoupon(int $userId, int $couponId, float $orderAmount): array
    {
        $userCoupon = UserCoupon::with('coupon')
            ->where('user_id', $userId)
            ->where('coupon_id', $couponId)
            ->where('status', 0)
            ->find();

        if (!$userCoupon) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.not_available')
            ];
        }

        $coupon = $userCoupon->coupon;

        // 检查有效期
        if (now() < $coupon['valid_start_date'] || now() > $coupon['valid_end_date']) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.expired')
            ];
        }

        // 检查最低使用金额
        if ($orderAmount < $coupon['min_amount']) {
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.min_amount_not_met')
            ];
        }

        return [
            'code' => HttpCode::SUCCESS,
            'data' => $coupon
        ];
    }

    /**
     * 计算优惠金额
     */
    public function calculateDiscount(array $couponData, float $orderAmount): float
    {
        $discount = 0;

        if ($couponData['coupon_type'] == 1) { // 折扣券
            $discount = $orderAmount * (1 - $couponData['discount_value'] / 100);
        } elseif ($couponData['coupon_type'] == 2) { // 满减券
            $discount = $couponData['discount_value'];
        } elseif ($couponData['coupon_type'] == 3) { // 随机券
            $discount = $couponData['discount_value'];
        }

        // 确保优惠不超过订单金额
        return min($discount, $orderAmount);
    }

    /**
     * 使用优惠券
     */
    public function useCoupon(int $userId, int $couponId, int $orderId): array
    {
        try {
            Db::startTrans();

            $userCoupon = UserCoupon::where('user_id', $userId)
                ->where('coupon_id', $couponId)
                ->where('status', 0)
                ->find();

            if (!$userCoupon) {
                Db::rollback();
                return [
                    'code' => HttpCode::ERROR,
                    'msg' => lang('coupon.not_available')
                ];
            }

            // 标记为已使用
            $userCoupon->save([
                'status' => 1,
                'used_at' => now(),
                'order_id' => $orderId
            ]);

            Db::commit();

            return [
                'code' => HttpCode::SUCCESS,
                'msg' => lang('coupon.use_success')
            ];
        } catch (\Throwable $th) {
            Db::rollback();
            return [
                'code' => HttpCode::ERROR,
                'msg' => lang('error.db')
            ];
        }
    }
}