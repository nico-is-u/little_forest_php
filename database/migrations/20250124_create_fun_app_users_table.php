<?php
declare(strict_types=1);

use think\migration\Migrator;

class CreateFunAppUsersTable extends Migrator
{
    /**
     * 创建表
     */
    public function up()
    {
        $this->table('app_users', [
            'engine'      => 'InnoDB',
            'charset'     => 'utf8mb4',
            'collation'   => 'utf8mb4_unicode_ci',
            'comment'     => '小程序用户表',
            'signed'      => false
        ])
            ->addColumn('openid', 'string', ['limit' => 100, 'null' => false, 'comment' => '微信OpenID'])
            ->addColumn('unionid', 'string', ['limit' => 100, 'null' => true, 'comment' => '微信UnionID'])
            ->addColumn('nickname', 'string', ['limit' => 50, 'null' => true, 'comment' => '昵称'])
            ->addColumn('avatar', 'string', ['limit' => 255, 'null' => true, 'comment' => '头像'])
            ->addColumn('mobile', 'string', ['limit' => 20, 'null' => true, 'comment' => '手机号'])
            ->addColumn('gender', 'integer', ['limit' => 1, 'default' => 0, 'signed' => false, 'comment' => '性别 0:未知 1:男 2:女'])
            ->addColumn('city', 'string', ['limit' => 50, 'null' => true, 'comment' => '城市'])
            ->addColumn('province', 'string', ['limit' => 50, 'null' => true, 'comment' => '省份'])
            ->addColumn('country', 'string', ['limit' => 50, 'null' => true, 'comment' => '国家'])
            ->addColumn('language', 'string', ['limit' => 20, 'null' => true, 'comment' => '语言'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'signed' => false, 'comment' => '状态 1:正常 0:禁用'])
            ->addColumn('last_login_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '最后登录时间'])
            ->addColumn('create_time', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['null' => true, 'signed' => false, 'comment' => '删除时间'])
            ->addIndex(['openid'], ['unique' => true])
            ->addIndex(['mobile'])
            ->addIndex(['status'])
            ->create();
    }

    /**
     * 删除表
     */
    public function down()
    {
        $this->table('app_users')->drop()->save();
    }
}
