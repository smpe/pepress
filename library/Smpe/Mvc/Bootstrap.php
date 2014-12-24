<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

//define('REQUEST_TIME_FLOAT', microtime(true));
mb_internal_encoding('utf-8');
header("Content-type: text/html; charset=UTF-8");

class Smpe_Mvc_Bootstrap
{
    /**
     * @var string
     */
    public static $time = '';

    /**
     * @var int
     */
    public static $timestamp = 0;

    /**
     * @var string
     */
    public static $workingDir = '';

    /**
     * @var array
     */
    public static $request = array(
        'module' => '',
        'controller' => 'Index',
        'action' => 'Index',
        'args' => array(),
        'protocol' => '',
        'host' => '',
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
        self::$time = date('Y-m-d H:i:s');
        self::$timestamp = time();
    }

    /**
     * Domain
     */
    private static function initDomain() {
        self::$request['protocol'] = $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
        self::$request['host'] = $_SERVER['HTTP_HOST'];
        self::$request['domain'] = strstr($_SERVER['HTTP_HOST'], '.');
        if(empty(self::$request['domain'])) {
            self::$request['domain'] = $_SERVER['HTTP_HOST'];
        }
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
            $path = sprintf("%s/Config.php", self::$workingDir);
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
        self::initArgs();

        //module
        if(empty(self::$request['args'][0]) || self::$request['args'][0] == 'index.php'){
            self::$request['module'] = Config::$defaultModule;
        } else {
            self::$request['module'] = array_shift(self::$request['args']);
        }

        //controller
        if(!empty(self::$request['args'][0])) {
            self::$request['controller'] = array_shift(self::$request['args']);
        }

        //action
        if(!empty(self::$request['args'][0])) {
            self::$request['action'] = array_shift(self::$request['args']);
        }
    }

    /**
     *
     * @throws Exception
     */
    private static function initActionName() {
        $a = ord(self::$request['action']);
        if($a < 65 || $a > 90) {
            throw new Exception('The first letter of the method name must be capitalized: '.self::$request['action']);
        }
    }

    /**
     *
     */
    private static function initArgs() {
        //vDir
        if(Config::$isRewrite){
            $path = parse_url(Smpe_Mvc_Filter::string('REQUEST_URI', INPUT_SERVER), PHP_URL_PATH);
            $path = substr($path, strlen(config::$vDir));
        } else {
            config::$vDir = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $path = Smpe_Mvc_Filter::string('p', INPUT_GET);
        }
        
        //args
        self::$request['args'] = explode('/', $path);
        
        //Remove "/" and "" at begin.
        if(empty(self::$request['args'][0]) || self::$request['args'][0] == '/') {
            array_shift(self::$request['args']);
        }
    }

    /**
     * Controller
     * @throws Exception
     */
    private static function initController() {
        $s = "%s/controller/%s/%sController.php";
        $path = sprintf($s, self::$workingDir, self::$request['module'], self::$request['controller']);
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
        $className = sprintf("%s_%sController", self::$request['module'], self::$request['controller']);
        if(!class_exists($className)){
            throw new Exception('Class not exists: '.$className);
        }

        self::$action  = new $className(self::$request);

        if(!method_exists(self::$action, self::$request['action'])){
            throw new Exception('Method not exists: '.$className.'->'.self::$request['action']);
        }

        self::$action->init();

        return call_user_func_array(array(self::$action, self::$request['action']), self::$request['args']);
    }

    /**
     * Autoload
     * @param $className
     */
    private static function autoload($className) {
        $path = str_replace(array('_', '\\'), array('/', '/'), $className);
        $path = sprintf('%s/library/%s.php', self::$workingDir, $path);
        
        if(is_file($path)) {
            require $path;
        } else {
            $m = 'File not exists: '.$path."\n";
            file_put_contents(self::$workingDir.'/data/log/smpe.log', $m, FILE_APPEND|LOCK_EX);
        }
    }

    /**
     * Result
     * @param $r
     */
    private static function result($r) {
        if(is_null(self::$action)){
            var_dump($r);
            return;
        }

        if(is_null($r)) {
            return;
        }

        if(!is_array($r)){
            $r = array('data'=>$r, 'msg'=>'');
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
            self::initActionName();
            self::initController();
            $r = self::initAction();
        } catch (Exception $e) {
            $r = array('data'=>-1, 'msg'=>$e->getMessage());
        }

        self::result($r);

        if(Config::$environment < 2) {
            $t = number_format(microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'], 4, '.', '');
            $m = sprintf("%s: Consuming time %ss (%s)\n", self::$time, $t, $_SERVER['REQUEST_URI']);
            //error_log($m, 3, self::$workingDir.'/data/log/smpe.log'); //Conflict
            file_put_contents(self::$workingDir.'/data/log/smpe.log', $m, FILE_APPEND|LOCK_EX);
        }
    }

    /**
     * Write log message.
     * @param string $message
     * @param string $scope
     * @param array $options
     */
    public static function log($message, $scope = 'smpe', $options = array()) {

    }
}
