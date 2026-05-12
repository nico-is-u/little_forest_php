<?php
declare (strict_types = 1);

namespace app\controller\api;

use think\Request;
use think\facade\Cache;
use think\facade\Lang;
use think\facade\View;
use think\facade\Filesystem;

use think\middleware\annotation\RateLimit;

use app\common\HttpCode;

class OtherController
{

    /* 缓存（手机号）前缀 */
    private const CODE_HASH_PREFIX  = 'sms_hash_';

    /**
     * 拉取短信验证码
     */
    #[RateLimit(rate: "2/m")]
    public function sms_code(Request $request)
    {
        /* 验证手机号码 */
        $phone = $request->param('phone');
        if (!$phone || !preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('wrong.phone')
            ]);
        }

        /* 60秒内限流只能拉取一次验证码,如果允许则返回哈希给前端，验证不在这里做 */    
        $cacheKey = self::CODE_HASH_PREFIX . $phone;
        
        /**
         * 生成短信验证码，调用三方API
         */

        $sms_code = str_pad((string)random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        /* 验证码存储60秒，过期后自动删除 */
        Cache::set($cacheKey, $sms_code, 60);

        /* 这里应该调用三方API发送短信验证码，成功后才返回成功响应 */
        /* 这里为了演示直接返回成功响应，并将验证码存储在缓存中，实际项目中请勿将验证码返回给前端 */
        
        return json([
            'code' => HttpCode::SUCCESS,
            'msg' => lang('success.sms_code'),
            'data' => [
                'sms_code' => $sms_code // 仅用于测试，实际项目中请勿将验证码返回给前端
            ]
        ]);
        

    }

    /**
     * 上传文件（公共）
     * 暂时只处理图片
     */
    public function upload(Request $request)
    {
        /* 验证文件是否存在 */
        try {
            $file = $request->file('file');

            /* 验证文件格式 */
            $acceptTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file->getMime(), $acceptTypes)) {
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.params')
                ]);
            }

            /* 验证文件尺寸 */
            $maxSize = .5 * 1024 * 1024; // 0.5MB
            if ($file->getSize() > $maxSize) {
                return json([
                    'code' => HttpCode::ERROR,
                    'msg' => lang('wrong.upload_max')
                ]);
            }

        } catch (\Exception $e) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('wrong.params')
            ]);
        }

        /* 得到上传场景 */
        $scene = $request->param('scene', 'portrait'); // 默认头像上传
        
        /* 生成文件路径 */
        $authUser = $request->authUser;
        switch ($scene) {
            case 'portrait':
            default:
                $putPath = 'portrait/'. $authUser['user_code'];
                $putFileName = '1.' . $file->getOriginalExtension();
                break;
        }
        
        /* 存储文件 */
        try {
            
            /* 如果上传头像，清除旧头像 */
            if ($scene === 'portrait') {

                $oldFiles = Filesystem::listContents('uploads/images')
                ->filter(function ($entry) {
                    // 只筛选文件
                    return $entry->isFile();
                })
                ->toArray();

                if(!empty($oldFiles)){
                    Filesystem::delete($oldFiles);
                }
            }

            /* 上传资源 */
            $savePath = Filesystem::putFileAs($putPath, $file, $putFileName);
            
            return json([
                'code' => HttpCode::SUCCESS,
                'msg' => lang('success.upload'),
                'data' => [
                    'url' => Filesystem::url($savePath)
                ]
            ]);

        } catch (\Exception $e) {
            return json([
                'code' => HttpCode::ERROR,
                'msg' => lang('error.upload')
            ]);
        }


    }

    /**
     * 测试接口
     */
    public function only_test(){
        return View::fetch('api/only_test');
    }

}
