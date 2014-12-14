<?php
class system_user
{
    /**
     * @var smpe_mysql
     */
    private static $data = null;

    /**
     * @return smpe_mysql
     */
    public static function data()
    {
        if(is_null(self::$data)) {
            self::$data = new smpe_mysql('system', 'user', 'user_id');
        }
        return self::$data;
    }

}