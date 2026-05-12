<?php

use think\migration\Migrator;
use think\migration\db\Column;

use app\service\UserService;

class UserInfo extends Migrator
{
    public function change(){
        $table  =  $this->table('user_info');
        $table->addColumn('user_id', 'integer', array('limit' => 11,'default'=>0,'comment'=>'用户ID'))
        ->addColumn('nickname', 'string', array('default'=>'','comment'=>'昵称'))
        ->addColumn('realname', 'string' , array('default' => '', 'comment' => '真实姓名'))
        ->addColumn('level', 'integer', array('limit' => 2, 'default' => 1,'comment'=>'等级'))
        ->addColumn('integral', 'integer', array('default'=>0,'comment'=>'积分（通用）'))
        ->addColumn('balance', 'decimal', array('precision' => 10, 'scale' => 2, 'default' => '0.00', 'comment' => '余额'))
        ->addColumn('portrait', 'string', array('default'=>'','comment'=>'头像'))
        ->addColumn('sex', 'integer', array('limit' => 1, 'default' => 1, 'comment' => '性别：0未知，1男，2女'))
        ->addColumn('birthday', 'date', array('null' => true, 'comment' => '生日'))
        ->addColumn('signature', 'string', array('default'=>'','comment'=>'个性签名'))
        ->addColumn('id_card', 'string', array('limit'=>'12','default'=>'','comment'=>'身份证号码'))
        ->addColumn('province', 'string', array('limit'=>'10','default'=>'','comment'=>'省份'))
        ->addColumn('city', 'string', array('limit'=>'10','default'=>'','comment'=>'城市'))
        ->addColumn('district', 'string', array('limit'=>'30','default'=>'','comment'=>'区县'))
        ->addIndex(array('user_id'), array('unique'  =>  true))
        ->create();

        /* 插入测试数据 */
        UserService::insertTestUser();
        
    }

}
