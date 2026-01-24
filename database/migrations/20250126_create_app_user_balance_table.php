<?php
declare(strict_types=1);

use think\migration\Migrator;

class CreateAppUserBalanceTable extends Migrator
{
    /**
     * 创建表
     */
    public function up()
    {
        $this->table('app_user_balance', [
            'engine'      => 'InnoDB',
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'comment'     => '用户余额表',
            'signed'      => false
        ])
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false, 'comment' => '用户ID'])
            ->addColumn('balance', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'default' => '0.00', 'comment' => '当前余额'])
            ->addColumn('total_recharge', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'default' => '0.00', 'comment' => '总充值金额'])
            ->addColumn('total_consume', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => false, 'default' => '0.00', 'comment' => '总消费金额'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'signed' => false, 'comment' => '状态 1:正常 0:冻结'])
            ->addColumn('create_time', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '删除时间'])
            ->addIndex(['user_id'], ['unique' => true])
            ->addIndex(['status'])
            ->create();
    }

    /**
     * 删除表
     */
    public function down()
    {
        $this->table('app_user_balance')->drop()->save();
    }
}
