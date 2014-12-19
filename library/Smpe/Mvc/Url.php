<?php
class Smpe_Mvc_Url
{
    public static function http() {
        return self::fullUrl('http', func_get_args());
    }

    public static function https() {
        return self::fullUrl('https', func_get_args());
    }

    public static function pub($path) {
        return $path.'?time='.Config::$version;
    }

    public static function theme($path) {
        return '/src/themes/default'.$path.'?time='.Config::$version;
    }

    private static function fullUrl($schema, $args) {
        $module = array_shift($args);
        $domain = empty(Config::$modules[$module]['listen']) ? Smpe_Mvc_Bootstrap::$request['host'] : Config::$modules[$module]['listen'];
        $url = sprintf("%s://%s", $schema, $domain);
        if(empty($args)) {
            return $url;
        } else {
            return sprintf('%s/%s/%s', $url, $module, implode('/', $args));
        }
    }
}
