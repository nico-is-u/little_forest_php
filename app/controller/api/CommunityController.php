<?php

declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use app\service\CommunityService;
use app\common\HttpCode;

class CommunityController
{
    /**
     * 获取可选小区列表
     */
    public function list(Request $request, CommunityService $communityService)
    {
        $status = $request->param('status');
        $keyword = $request->param('keyword');

        $params = [];
        if ($status !== null) {
            $params['status'] = intval($status);
        }
        if ($keyword !== null) {
            $params['keyword'] = trim($keyword);
        }

        $result = $communityService->getList($params);

        if ($result['code'] === HttpCode::SUCCESS) {
            $data = [];
            foreach ($result['data'] as $community) {
                $data[] = [
                    'id' => $community['id'],
                    'code' => $community['code'],
                    'name' => $community['name'],
                    'status' => $community['status'],
                    'address' => $community['address'],
                    'province' => $community['province'],
                    'city' => $community['city'],
                    'district' => $community['district'],
                    'latitude' => $community['latitude'],
                    'longitude' => $community['longitude'],
                    'service_radius' => $community['service_radius'],
                    'sort' => $community['sort'],
                    'created_at' => $community['created_at'],
                    'updated_at' => $community['updated_at'],
                ];
            }

            return json([
                'code' => HttpCode::SUCCESS,
                'data' => $data,
            ]);
        }

        return json($result);
    }
}
