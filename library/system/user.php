<?php
namespace Modules\System;

class User
{
    /**
     * @var Smpe_Db_Mysql
     */
    private static $data = null;

    /**
     * @return Smpe_Db_Mysql
     */
    public static function data()
    {
        if(is_null(self::$data)) {
            self::$data = new Smpe_Db_Mysql('System', 'user', 'user_id');
        }
        return self::$data;
    }
}
