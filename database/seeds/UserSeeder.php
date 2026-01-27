<?php
declare(strict_types=1);

use think\migration\Seeder;

class UserSeeder extends Seeder
{
    /**
     * 执行数据填充
     */
    public function run(): void
    {
        // 插入用户记录
        $this->table('app_users')->insert([
            [
                'openid' => 'test_openid_' . time(),
                'nickname' => '测试员',
                'avatar' => 'upload/0124/bd53aa611fa4c95a56d59d9f25f35ec5.jpg',
                'mobile' => '13000000001',
                'gender' => 0,
                'status' => 1,
                'create_time' => time(),
            ]
        ])->save();

        // 获取刚插入的用户ID
        $userId = $this->getAdapter()->getConnection()->lastInsertId();

        // 插入余额记录
        $this->table('app_user_balance')->insert([
            [
                'user_id' => $userId,
                'balance' => 1000000.00,
                'total_recharge' => 0.00,
                'total_consume' => 0.00,
                'status' => 1,
                'create_time' => time(),
                'update_time' => null,
                'delete_time' => null,
            ]
        ])->save();
    }
}
