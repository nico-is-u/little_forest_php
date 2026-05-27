<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Users extends Migrator
{
    public function change()
    {
        $table  =  $this->table('users');
        $table->addColumn('user_code', 'string', array('limit' => 11,'default'=>'','comment'=>'用户代码（美化）'))
        ->addColumn('username', 'string', array('limit' => 15,'default'=>'','comment'=>'用户名'))
        ->addColumn('phone', 'string' , array('limit' => 15, 'default' => '', 'comment' => '手机号码'))
        ->addColumn('password', 'string', array('limit' => 32,'comment'=>'密码')) 
        ->addColumn('last_login_ip', 'string' , array('limit' => 11,'default'=>'','comment'=>'最后登录IP'))
        ->addColumn('last_login_time', 'datetime' , array('comment'=>'最后登录时间'))
        ->addColumn('is_delete', 'boolean' , array('limit' => 1,'default' => 0,'comment'=>'删除状态, 1已删除'))
        ->addIndex('user_code', array('unique'  =>  true))
        ->addIndex('phone', array('unique'  =>  true))
        ->create();
    }
}
