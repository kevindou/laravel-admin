<?php

namespace App\Traits;

/**
 * Trait CommandTrait 命名行 trait
 * @package App\Traits
 */
trait CommandTrait
{
    /**
     * 判断是否 int 类型
     *
     * @param string $type
     *
     * @return bool
     */
    protected function isInt($type)
    {
        return $this->isStartWith(['tinyint', 'smallint', 'mediumint', 'int', 'bigint'], $type);
    }

    /**
     * 判断是否string 类型
     *
     * @param string $type
     *
     * @return bool|array
     */
    protected function isString($type)
    {
        if ($this->isStartWith(['char', 'varchar', 'text'], $type)) {
            preg_match('/\d+/', $type, $array);
            $return = ['min' => 2];
            if ($array) {
                $return['max'] = array_get($array, 0);
            }

            return $return;
        }

        return false;
    }

    /**
     * 是否存在数组中数据的开头
     *
     * @param array  $array
     * @param string $type
     *
     * @return bool
     */
    protected function isStartWith($array, $type)
    {
        $is_start_with = false;
        foreach ($array as $start) {
            if (starts_with($type, $start)) {
                $is_start_with = true;
                break;
            }
        }

        return $is_start_with;
    }
}