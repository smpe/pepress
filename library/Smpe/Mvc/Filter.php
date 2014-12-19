<?php
class Smpe_Mvc_Filter
{
    /**
     * Gets a specific external variable by name and optionally filters it (Unsafe)
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return array
     */
    public static function arr($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_DEFAULT, array('flags' => FILTER_REQUIRE_ARRAY|FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param mixed $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return boolean
     */
    public static function boolean($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_VALIDATE_BOOLEAN, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function email($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_VALIDATE_EMAIL, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return float
     */
    public static function float($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_VALIDATE_FLOAT, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it (Unsafe)
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function html($queryName, $type = INPUT_POST)
    {
        $returnValue = filter_input($type, $queryName, FILTER_UNSAFE_RAW, array('flags' => FILTER_NULL_ON_FAILURE));

        return strip_tags($returnValue, '<a><b><br><center><code><dd><div><dl><dt><em><font><h1><h2><h3><h4><h5><h6><hr><i><img><label><li><ol><p><pre><span><strike><strong><table><tbody><td><tfoot><th><thead><tr><u><ul>' );
    }

    /**
     * Gets a specific external variable by name and optionally filters it (Unsafe)
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function htmlStrict($queryName, $type = INPUT_POST)
    {
        $returnValue = filter_input($type, $queryName, FILTER_UNSAFE_RAW, array('flags' => FILTER_NULL_ON_FAILURE));

        return strip_tags($returnValue, '<b><br><center><code><dd><div><dl><dt><em><font><h1><h2><h3><h4><h5><h6><hr><i><img><label><li><ol><p><pre><span><strike><strong><table><tbody><td><tfoot><th><thead><tr><u><ul>' );
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return int
     */
    public static function int($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_VALIDATE_INT, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function order($queryName = 'order', $type = INPUT_GET)
    {
        //user_id-0|nickname-1|latest_login_time-1
        $orderStr = self::string($queryName, $type);

        if(empty($orderStr)){
            return array();
        }

        $result = array();

        $order = explode('--', $orderStr);

        foreach ($order as $key => $value) {
            $item = explode('-', $value);
            $result[$item[0]] = $item[1];
        }

        return $result;
    }

    /**
     * Gets a specific external variable by name and optionally filters it (Unsafe)
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function raw($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_UNSAFE_RAW, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * The translations performed are:
     *  '&' (ampersand) becomes '&amp;'
     *  '"' (double quote) becomes '&quot;' when ENT_NOQUOTES is not set.
     *  ''' (single quote) becomes '&#039;' only when ENT_QUOTES is set.
     *  '<' (less than) becomes '&lt;'
     *  '>' (greater than) becomes '&gt;'
     *
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function string($queryName, $type = INPUT_POST)
    {
        //[2013-05-23]过滤敏感词语
        return filter_input($type, $queryName, FILTER_SANITIZE_SPECIAL_CHARS, array('flags' => FILTER_NULL_ON_FAILURE));
    }

    /**
     * Gets a specific external variable by name and optionally filters it
     * @param string $queryName
     * @param int $type INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV
     * @return string
     */
    public static function url($queryName, $type = INPUT_POST)
    {
        return filter_input($type, $queryName, FILTER_VALIDATE_URL, array('flags' => FILTER_NULL_ON_FAILURE));
    }
}
