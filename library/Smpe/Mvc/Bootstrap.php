<?php
//define('REQUEST_TIME', microtime(true));
mb_internal_encoding('utf-8');
header("Content-type: text/html; charset=UTF-8");

class Smpe_Mvc_Bootstrap
{
    /**
     * @var string
     */
    private static $workingDir = '';

    /**
     * @var array
     */
    private static $request = array(
        'module' => '',
        'controller' => '',
        'action' => '',
        'args' => array(),
        'domain' => '',
    );

    /**
     * @var object
     */
    private static $action = null;

    /**
     * Working directory
     * @param $p
     */
    private static function initWorkingDir($p) {
        self::$workingDir = $p;
    }

    /**
     * Domain
     */
    private static function initDomain() {
        self::$request['domain'] = strstr($_SERVER['HTTP_HOST'], '.');
    }

    /**
     * Autoload class
     */
    private static function initAutoload() {
        spl_autoload_register('Smpe_Mvc_Bootstrap::autoload');
    }

    /**
     * @param string $path
     * @throws Exception
     */
    private static function initConfig($path = '') {
        if(empty($path)){
            $path = sprintf("%s/config%s.php", self::$workingDir, self::$request['domain']);
        }

        if(is_file($path)) {
            require $path;
        } else {
            throw new Exception('Cannot load configuration file: '.$path);
        }
    }

    /**
     * Log
     */
    private static function initLog() {
        if(Config::$environment < 2) { // < staging
            ini_set('error_reporting', E_ALL | E_STRICT);
            ini_set('log_errors', 0);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
    }

    /**
     * Request
     */
    private static function initRequest() {
        if(Config::$isRewrite){
            $path = parse_url(Smpe_Mvc_filter::string('REQUEST_URI', INPUT_SERVER), PHP_URL_PATH);
            //vDir
            $path = substr($path, strlen(config::$vDir));
        } else {
            $path = Smpe_Mvc_filter::string('p', INPUT_GET);
        }
        
        //args
        self::$request['args'] = explode('/', $path);
        //Remove "/" and "" at begin.
        if(empty(self::$request['args'][0]) || self::$request['args'][0] == '/') {
            array_shift(self::$request['args']);
        }

        //module
        self::$request['module'] = (empty(self::$request['args'][0]) || self::$request['args'][0] == 'index.php') ? 'System' : array_shift(self::$request['args']);
        //controller
        self::$request['controller'] = empty(self::$request['args'][0]) ? 'index' : array_shift(self::$request['args']);
        //action
        self::$request['action'] = empty(self::$request['args'][0]) ? 'index' : array_shift(self::$request['args']);
    }

    /**
     * Controller
     * @throws Exception
     */
    private static function initController() {
        $path = sprintf("%s/Controller/%s/%s.php", self::$workingDir, self::$request['module'], self::$request['controller']);
        if(!is_file($path)){
            throw new Exception('Cannot load controller file: '.$path);
        }

        require $path;
    }

    /**
     * Action
     * @throws Exception
     */
    private static function initAction() {
        $className = sprintf("Controller_%s_%s", self::$request['module'], self::$request['controller']);
        if(!class_exists($className)){
            throw new Exception('Class not exists: '.$className);
        }

        self::$action  = new $className();

        if(!method_exists(self::$action, self::$request['action'])){
            throw new Exception('Method not exists: '.$className.'->'.self::$request['action']);
        }

        self::$action->init(self::$workingDir, self::$request);

        self::$action->load();

        $r = call_user_func_array(array(self::$action, self::$request['action']), self::$request['args']);
        //if(!isset($r['data']) || !isset($r['msg'])){
        //    throw new Exception(var_export($r, true));
        //}

        //self::$action->result($r);
        return $r;
    }

    /**
     * Autoload
     * @param $className
     */
    private static function autoload($className) {
        $path = str_replace(array('_', '\\'), array('/', '/'), $className);
        $path = sprintf('%s/Library/%s.php', self::$workingDir, $path);
        if(is_file($path)) {
            require $path;
        }
    }

    /**
     * result
     * @param $r
     */
    private static function result($r) {
        if(is_null($r)) {
            return;
        }

        if(!is_array($r)){
            $r = array('data'=>$r, 'msg'=>'');
        }

        if(is_null(self::$action)){
            echo $r['msg'];
            return;
        }

        if($_SERVER["REQUEST_METHOD"] == 'POST') {
            self::$action->json($r);
        } else { //GET
            self::$action->error($r['msg'], $r['data']);
        }
    }

    /**
     * Run application
     * @param $workingDir
     * @param string $configPath
     */
    public static function run($workingDir, $configPath = '') {
        try {
            self::initWorkingDir($workingDir);
            self::initDomain();
            self::initAutoload();
            self::initConfig($configPath);
            self::initLog();
            self::initRequest();
            self::initController();
            $r = self::initAction();
        } catch (Exception $e) {
            $r = array('data'=>array(), 'msg'=>$e->getMessage());
        }

        self::result($r);
    }
}
