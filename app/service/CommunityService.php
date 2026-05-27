<?php

declare (strict_types = 1);

namespace app\service;

use app\model\Community;
use app\common\HttpCode;

class CommunityService
{
    /**
     * 获取小区列表
     */
    public function getList(array $params = []): array
    {
        $query = Community::order('sort', 'asc')->order('id', 'desc');

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['keyword'])) {
            $query->where('name|address', 'like', '%' . $params['keyword'] . '%');
        }

        $communities = $query->select();

        return [
            'code' => HttpCode::SUCCESS,
            'data' => $communities,
        ];
    }

    /**
     * 插入测试小区数据
     */
    public static function insertTestCommunity()
    {
        $testData = [
            [
                'name' => '金祥花园',
                'code' => 'JXHY',
                'status' => 1,
                'address' => '海滨大道南23号',
                'province' => '广东省',
                'city' => '湛江市',
                'district' => '霞山区',
                'service_radius' => 500,
                'sort' => 1,
            ],
        ];

        foreach ($testData as $data) {
            if (!Community::where('name', $data['name'])->find()) {
                Community::create($data);
            }
        }
    }
}
