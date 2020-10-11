<?php

namespace App;

/**
 * 这个类用来提供需要监视的星系和其星域的映射。
 * @package App
 */
class NeedWatchSystemMap
{
    private const FILE_PATH = __DIR__ . '/needwatchsystems.json';

    public static function getMap(): array
    {
        static $cache;
        if ($cache === null) {
            $cache = json_decode(file_get_contents(self::FILE_PATH), true);
        }
        return $cache;
    }

    /**
     * 获得所有需要监视的星系名列表
     * @return array 所有需要监视的星系名列表
     */
    public static function getSystemList(): array
    {
        static $cache = [];
        if ($cache === []) {
            foreach (self::getMap() as $systems) {
                $cache = array_merge($cache, $systems);
            }
        }
        return $cache;
    }

    /**
     * 用于前端级联选择器的选项提供器
     * @return string 级联选择器的选项JSON
     */
    public static function toJson()
    {
        $map = self::getMap();
        $data = [];
        foreach ($map as $region => $systems) {
            $children = [];
            foreach ($systems as $system) {
                $children[] = ['label' => $system, 'value' => $system];
            }
            $data[] = [
                'label' => $region,
                'value' => $region,
                'children' => $children,
            ];
        }

        return json_encode($data);
    }
}
