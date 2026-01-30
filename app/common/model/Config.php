<?php

namespace app\common\model;

use think\Model;

class Config extends Model
{
    // 设置数据表名
    protected $name = 'config';
    
    // 自动时间戳
    protected $autoWriteTimestamp = false;
    
    /**
     * 根据分组获取配置
     * @param string $group 分组名称
     * @param bool $status 状态筛选
     * @return array
     */
    public static function getConfigsByGroup($group = 'app', $status = true)
    {
        $query = self::where('group', $group);
        
        if ($status !== null) {
            $query->where('status', $status ? 1 : 0);
        }
        
        return $query->column('value', 'code');
    }
    
    /**
     * 批量更新配置
     * @param array $data 配置数据 [code => value]
     * @param string $group 分组名称
     * @return bool
     */
    public static function updateConfigs($data, $group = 'app')
    {
        if (empty($data)) {
            return true;
        }
        
        $result = true;
        foreach ($data as $code => $value) {
            $updateResult = self::where('code', $code)
                ->where('group', $group)
                ->update(['value' => $value]);
            
            if ($updateResult === false) {
                $result = false;
                break;
            }
        }
        
        return $result;
    }
    
    /**
     * 获取单个配置值
     * @param string $code 配置代码
     * @param string $group 分组名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function getValue($code, $group = 'app', $default = null)
    {
        $config = self::where('code', $code)
            ->where('group', $group)
            ->value('value');
            
        return $config !== null ? $config : $default;
    }
}