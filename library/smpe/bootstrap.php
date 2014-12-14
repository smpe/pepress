<?php
//define('REQUEST_TIME', microtime(true));
mb_internal_encoding('utf-8');
header("Content-type: text/html; charset=UTF-8");

//debug control
/*if(config::environment < 2) { // < staging
	ini_set('error_reporting', E_ALL | E_STRICT);
	ini_set('log_errors', 0);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}*/

class smpe_bootstrap
{
    /**
     * @var array
     */
    private static $request = array(
        'module' => '',
        'controller' => '',
        'action' => '',
        'args' => array(),
        'working_dir' => '',
        'domain' => '',
    );

    /**
     * @param $p
     */
    private static function initWorkingDir($p) {
        self::$request['working_dir'] = $p;
    }

    /**
     *
     */
    private static function initDomain() {
        self::$request['domain'] = strstr($_SERVER['HTTP_HOST'], '.');
    }

    /**
     * @throws Exception
     */
    private static function initConfig() {
        //FIXME: filter IP address.
        $conf = sprintf("%s/conf/config%s.php", self::$request['working_dir'], self::$request['domain']);
        if(is_file($conf)) {
            require $conf;
        } else {
            throw new Exception('Cannot load configuration file: '.$conf);
        }
    }

    /**
     * @param $className
     */
    public static function autoload($className) {
        $path = sprintf('%s/library/%s.php', self::$request['working_dir'], str_replace('_', DIRECTORY_SEPARATOR, $className));
        if(is_file($path)) {
            require $path;
        }
    }

    /**
     *
     */
    private static function initRequest() {
        if(config::$isRewrite){
            $path = parse_url(smpe_filter::string('REQUEST_URI', INPUT_SERVER), PHP_URL_PATH);
            //vDir
            $path = substr($path, strlen(config::$vDir));
        } else {
            $path = smpe_filter::string('p', INPUT_GET);
        }
        
        //args
        self::$request['args'] = explode('/', $path);
        //Remove "/" at begin.
        array_shift(self::$request['args']);
        //module
        self::$request['module'] = (empty(self::$request['args'][0]) || self::$request['args'][0] == 'index.php') ? 'system' : array_shift(self::$request['args']);
        //controller
        self::$request['controller'] = empty(self::$request['args'][0]) ? 'index' : array_shift(self::$request['args']);
        //action
        self::$request['action'] = empty(self::$request['args'][0]) ? 'index' : array_shift(self::$request['args']);
    }

    /**
     * @throws Exception
     */
    private static function initController() {
        $path = sprintf("%s/controllers/%s/%s.php", self::$request['working_dir'], self::$request['module'], self::$request['controller']);
        if(!is_file($path)){
            throw new Exception('Cannot load controller file: '.$path);
        }

        require $path;
    }

    /**
     * Run application.
     * @throws Exception
     */
    private static function start() {
        $className = sprintf("controller_%s_%s", self::$request['module'], self::$request['controller']);
        if(!class_exists($className)){
            throw new Exception('Class not exists: '.$className);
        }

        $obj  = new $className();

        if(!method_exists($obj, self::$request['action'])){
            throw new Exception('Method not exists: '.$className.'->'.self::$request['action']);
        }

        $obj->init(self::$request);

        $obj->load();

        $r = call_user_func_array(array($obj, self::$request['action']), self::$request['args']);

        //if($init['data'] <= 0) {
        //    throw new Exception('Page run check failed: '.$init['msg']);
        //}
    }

    /**
     * @param $workingDir
     * @throws Exception
     */
    public static function run($workingDir) {
        self::initWorkingDir($workingDir);
        self::initDomain();
        self::initConfig();
        spl_autoload_register('smpe_bootstrap::autoload');
        self::initRequest();
        self::initController();
        self::start();
    }
}
