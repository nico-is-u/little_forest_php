<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Coupons extends Migrator
{
    public function change()
    {
        // 创建优惠券模板表
        $table = $this->table('coupons');
        $table->addColumn('coupon_code', 'string', array('limit' => 20, 'comment' => '优惠券代码'))
              ->addColumn('coupon_name', 'string', array('limit' => 50, 'comment' => '优惠券名称'))
              ->addColumn('coupon_type', 'integer', array('limit' => 1, 'default' => 1, 'comment' => '类型：1折扣，2满减，3随机'))
              ->addColumn('discount_value', 'decimal', array('precision' => 10, 'scale' => 2, 'default' => '0.00', 'comment' => '折扣/减价金额'))
              ->addColumn('min_amount', 'decimal', array('precision' => 10, 'scale' => 2, 'default' => '0.00', 'comment' => '最低使用金额'))
              ->addColumn('total_count', 'integer', array('default' => -1, 'comment' => '总数：-1无限'))
              ->addColumn('used_count', 'integer', array('default' => 0, 'comment' => '已使用数'))
              ->addColumn('valid_start_date', 'datetime', array('comment' => '有效期开始'))
              ->addColumn('valid_end_date', 'datetime', array('comment' => '有效期结束'))
              ->addColumn('status', 'integer', array('limit' => 1, 'default' => 1, 'comment' => '状态：1可用，2暂停，3下线'))
              ->addIndex('coupon_code', array('unique' => true))
              ->create();

        // 创建用户优惠券表
        $table2 = $this->table('user_coupons');
        $table2->addColumn('user_id', 'integer', array('comment' => '用户ID'))
               ->addColumn('coupon_id', 'integer', array('comment' => '优惠券ID'))
               ->addColumn('used_at', 'datetime', array('null' => true, 'comment' => '使用时间'))
               ->addColumn('order_id', 'integer', array('null' => true, 'comment' => '关联订单ID'))
               ->addColumn('status', 'integer', array('limit' => 1, 'default' => 0, 'comment' => '状态：0未使用，1已使用，2已过期'))
               ->addIndex(array('user_id', 'coupon_id'), array('unique' => true))
               ->create();
    }
}