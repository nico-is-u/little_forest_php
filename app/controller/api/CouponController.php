<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use app\service\CouponService;
use app\common\HttpCode;

class CouponController
{
    /**
     * 获取用户优惠券列表
     */
    public function list(Request $request, CouponService $couponService)
    {
        $authUser = $request->authUser;
        $status = $request->param('status'); // 可选：0未使用，1已使用，2已过期

        $result = $couponService->getUserCoupons($authUser['user_id'], $status);

        if ($result['code'] == HttpCode::SUCCESS) {
            // 格式化数据
            $data = [];
            foreach ($result['data'] as $userCoupon) {
                $coupon = $userCoupon->coupon;
                $data[] = [
                    'id' => $userCoupon['id'],
                    'coupon_id' => $coupon['id'],
                    'coupon_name' => $coupon['coupon_name'],
                    'coupon_type' => $coupon['coupon_type'],
                    'discount_value' => $coupon['discount_value'],
                    'min_amount' => $coupon['min_amount'],
                    'valid_end_date' => $coupon['valid_end_date'],
                    'status' => $userCoupon['status'],
                    'used_at' => $userCoupon['used_at'],
                ];
            }

            return json([
                'code' => HttpCode::SUCCESS,
                'data' => $data
            ]);
        }

        return json($result);
    }

    /**
     * 领取优惠券
     */
    public function receive(Request $request, CouponService $couponService)
    {
        $authUser = $request->authUser;
        $couponCode = $request->param('coupon_code');

        if (!$couponCode) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.code_required')
            ]);
        }

        return json($couponService->receiveCoupon($authUser['user_id'], $couponCode));
    }

    /**
     * 验证优惠券
     */
    public function validate(Request $request, CouponService $couponService)
    {
        $authUser = $request->authUser;
        $couponId = $request->param('coupon_id');
        $orderAmount = $request->param('order_amount', 0);

        if (!$couponId) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.id_required')
            ]);
        }

        $result = $couponService->validateCoupon($authUser['user_id'], $couponId, $orderAmount);

        if ($result['code'] == HttpCode::SUCCESS) {
            $coupon = $result['data'];
            $discount = $couponService->calculateDiscount($coupon, $orderAmount);

            return json([
                'code' => HttpCode::SUCCESS,
                'data' => [
                    'coupon' => $coupon,
                    'discount' => $discount,
                    'final_amount' => $orderAmount - $discount
                ]
            ]);
        }

        return json($result);
    }

    /**
     * 使用优惠券（订单确认时调用）
     */
    public function use(Request $request, CouponService $couponService)
    {
        $authUser = $request->authUser;
        $couponId = $request->param('coupon_id');
        $orderId = $request->param('order_id');

        if (!$couponId || !$orderId) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('coupon.use_params_required')
            ]);
        }

        return json($couponService->useCoupon($authUser['user_id'], $couponId, $orderId));
    }
}