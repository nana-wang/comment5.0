<?php

namespace common\helpers;


class Util
{
    public static function parseUrl($url)
    {
        if (strpos($url, '//') !== false) {
            return $url;
        }
        $url = explode("\r\n", $url);
        if (isset($url[1])) {
            $tmp = $url[1];
            unset($url[1]);
            $tmpParams = explode('&', $tmp);
            $params = [];
            foreach ($tmpParams as $tmpParam) {
                list($key, $value) = explode('=', $tmpParam);
                $params[$key] = $value;
            }
            $url = array_merge($url, $params);
        }
        return $url;
    }
}