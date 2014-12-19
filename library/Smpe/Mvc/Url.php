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
        return sprintf("%s%s?time=%d", Config::$vDir, $path, Config::$version);
    }

    public static function theme($path) {
        return sprintf("%s/src/themes/default%s?time=%d", Config::$vDir, $path, Config::$version);
    }

    private static function fullUrl($schema, $args) {
        $module = empty($args) ? Config::$defaultModule : array_shift($args);
        $domain = empty(Config::$modules[$module]['listen']) ? Smpe_Mvc_Bootstrap::$request['host'] : Config::$modules[$module]['listen'];
        $url = sprintf("%s://%s%s", $schema, $domain, Config::$vDir);
        if(empty($args)) {
            return $url;
        } else if(Config::$isRewrite) {
            return sprintf('%s/%s/%s', $url, $module, implode('/', $args));
        } else {
            return sprintf('%s?p=/%s/%s', $url, $module, implode('/', $args));
        }
    }
}
