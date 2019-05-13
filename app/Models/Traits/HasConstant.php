<?php
namespace App\Models\Traits;

trait HasConstant
{
    /**
     * 返回常量列表
     *
     * @return array
     */
    public static function getConstants($type = ''): array
    {
        return array_keys(static::constants($type));
    }

    /**
     * 获取常量对应中文名称
     *
     * @param $key
     * @return string
     */
    public static function getConstant($key, $type = ''): string
    {
        if (!$key and $key != 0) {
            return '';
        }

        return array_get(static::constants($type), $key, $key);
    }

    /**
     * 验证常量
     * @param string $key
     * @return bool
     */
    public static function validate(string $key, $type = ''): bool
    {
        if (!$key) {
            return false;
        }

        return (bool) array_key_exists($key, static::constants($type));
    }
}
