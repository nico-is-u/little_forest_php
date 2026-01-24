<?php
namespace app\common\service;

use Qcloud\Cos\Client;

use think\Exception;
use think\App;

class CosService extends AbstractService
{
    /**
     * COS客户端实例
     * @var Client|null
     */
    protected $client = null;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->initCosClient();
    }

    /**
     * 初始化COS客户端
     * @throws Exception
     */
    private function initCosClient()
    {
        // 读取配置
        $config = config('cos');
        if (empty($config['credentials']['secretId']) || empty($config['credentials']['secretKey']) || empty($config['bucket'])) {
            throw new Exception('COS配置未完善，请检查secretId/secretKey/bucket');
        }

        try {
            $this->client = new Client([
                'region'      => $config['region'],
                'schema'      => $config['schema'],
                'credentials' => [
                    'secretId'  => $config['credentials']['secretId'],
                    'secretKey' => $config['credentials']['secretKey']
                ]
            ]);
        } catch (\Exception $e) {
            throw new Exception('COS客户端初始化失败：' . $e->getMessage());
        }
    }

    /**
     * 上传文件到COS
     * @param string $localPath 本地文件绝对路径
     * @param string $cosPath COS存储路径（如uploads/2026/01/test.jpg）
     * @return array
     */
    public function upload(string $localPath, string $cosPath): array
    {
        if (!file_exists($localPath)) {
            return ['code' => 1, 'msg' => '本地文件不存在', 'data' => []];
        }

        $config = config('cos');
        try {
            $result = $this->client->putObject([
                'Bucket'      => $config['bucket'],
                'Key'         => $cosPath,
                'Body'        => fopen($localPath, 'rb'),
                'ContentType' => mime_content_type($localPath), // 自动识别MIME类型
            ]);

            // 拼接访问URL
            $url = "https://{$config['bucket']}.cos.{$config['region']}.myqcloud.com/{$cosPath}";
            return [
                'code' => 0,
                'msg'  => '上传成功',
                'data' => [
                    'url'      => $url,
                    'cos_path' => $cosPath,
                    'etag'     => $result['ETag'] ?? ''
                ]
            ];
        } catch (\Exception $e) {
            return ['code' => 1, 'msg' => 'COS上传失败：' . $e->getMessage(), 'data' => []];
        }
    }

    /**
     * 删除COS文件
     * @param string $cosPath COS存储路径
     * @return array
     */
    public function delete(string $cosPath): array
    {
        $config = config('cos');
        try {
            $this->client->deleteObject([
                'Bucket' => $config['bucket'],
                'Key'    => $cosPath
            ]);
            return ['code' => 0, 'msg' => '删除成功', 'data' => []];
        } catch (\Exception $e) {
            return ['code' => 1, 'msg' => 'COS删除失败：' . $e->getMessage(), 'data' => []];
        }
    }

    /**
     * 获取私有文件临时访问链接
     * @param string $cosPath COS存储路径
     * @param int|null $expire 有效期（秒，优先使用配置，传参则覆盖）
     * @return array
     */
    public function getTempUrl(string $cosPath, ?int $expire = null): array
    {
        $config = config('cos');
        $expire = $expire ?? $config['expire'];

        try {
            $signedUrl = $this->client->getObjectUrl(
                $config['bucket'],
                $cosPath,
                '+' . $expire . ' seconds'
            );
            return [
                'code' => 0,
                'msg'  => '获取临时链接成功',
                'data' => [
                    'url'    => (string)$signedUrl,
                    'expire' => $expire
                ]
            ];
        } catch (\Exception $e) {
            return ['code' => 1, 'msg' => '获取临时链接失败：' . $e->getMessage(), 'data' => []];
        }
    }

}