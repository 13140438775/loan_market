<?php
/**
 * Created by PhpStorm.
 * @author: gaoqiang@likingfit.com
 * @createTime: 2018/10/15 18:34
 */

namespace common\services;

class HelpService
{
    //转码 (php排序函数无法直接对utf-8编码汉字排序)
    private static function utf8ArraySort(&$array)
    {
        if (!isset($array) || !is_array($array)) {
            return false;
        }
        foreach ($array as $k => $v) {
            $array[$k] = iconv('UTF-8', 'GBK//IGNORE', $v);

        }
        return true;
    }

    /**
     * 二维数组根据分组
     * @param $arr
     * @param $key
     * @return array
     * @CreateTime 2018/9/20 10:59:55
     * @Author: heyafei@likingfit.com
     */
    public static function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        //分数排序
        foreach ($grouped as &$_group) {
            $arrSort = array();
            foreach($_group as $r_key => $r_val){
                foreach($r_val as $key => $val){
                    $arrSort[$key][$r_key] = $val;
                }
            }
        }
        return $grouped;
    }

    // 用*代替
    public static function starReplace($str, $length = 4)
    {
        return str_repeat('*', (mb_strlen($str) - $length)) . mb_substr($str, -$length);
    }

}