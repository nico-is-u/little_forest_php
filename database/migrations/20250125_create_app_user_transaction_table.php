<?php
declare(strict_types=1);

use think\migration\Migrator;

class CreateAppUserTransactionTable extends Migrator
{
    /**
     * 创建表
     */
    public function up()
    {
        $this->table('app_user_transaction', [
            'engine'      => 'InnoDB',
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'comment'     => '用户消费记录表',
            'signed'      => false
        ])
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false, 'comment' => '用户ID'])
            ->addColumn('type', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'signed' => false, 'comment' => '交易类型 1:充值 2:消费 3:退款 4:冻结 5:解冻'])
            ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'default' => '0.00', 'comment' => '交易金额'])
            ->addColumn('balance_after', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'default' => '0.00', 'comment' => '交易后余额'])
            ->addColumn('order_id', 'string', ['limit' => 50, 'null' => true, 'comment' => '关联订单ID'])
            ->addColumn('remark', 'string', ['limit' => 255, 'null' => true, 'comment' => '备注'])
            ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'signed' => false, 'comment' => '状态 1:成功 0:失败'])
            ->addColumn('create_time', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '更新时间'])
            ->addIndex(['user_id'])
            ->addIndex(['type'])
            ->addIndex(['status'])
            ->addIndex(['order_id'])
            ->addIndex(['create_time'])
            ->create();
    }

    /**
     * 删除表
     */
    public function down()
    {
        $this->table('app_user_transaction')->drop()->save();
    }
}
