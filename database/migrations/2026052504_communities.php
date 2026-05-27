<?php

declare (strict_types = 1);

use think\migration\Migrator;
use think\migration\db\Column;

use app\service\CommunityService;

class Communities extends Migrator
{
    public function change()
    {
        $table = $this->table('communities');
        $table->addColumn('name', 'string', ['limit' => 100, 'comment' => '小区名称'])
              ->addColumn('code', 'string', ['limit' => 50, 'comment' => '小区代号'])
              ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '状态：1正常，2暂停，3关闭'])
              ->addColumn('address', 'string', ['limit' => 255, 'null' => true, 'comment' => '详细地址'])
              ->addColumn('province', 'string', ['limit' => 50, 'null' => true, 'comment' => '省份'])
              ->addColumn('city', 'string', ['limit' => 50, 'null' => true, 'comment' => '城市'])
              ->addColumn('district', 'string', ['limit' => 50, 'null' => true, 'comment' => '区/县'])
              ->addColumn('latitude', 'decimal', ['precision' => 10, 'scale' => 7, 'null' => true, 'comment' => '纬度'])
              ->addColumn('longitude', 'decimal', ['precision' => 10, 'scale' => 7, 'null' => true, 'comment' => '经度'])
              ->addColumn('service_radius', 'integer', ['default' => 0, 'comment' => '服务半径（米）'])
              ->addColumn('sort', 'integer', ['default' => 0, 'comment' => '排序值，越小越靠前'])
              ->addColumn('created_at', 'datetime', ['null' => true, 'comment' => '创建时间'])
              ->addColumn('updated_at', 'datetime', ['null' => true, 'comment' => '更新时间'])
              ->addIndex('name', ['unique' => true])
              ->create();
        
        /* 插入测试数据 */
        CommunityService::insertTestCommunity();
    }
}
