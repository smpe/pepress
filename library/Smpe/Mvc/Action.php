<?php
class Smpe_Mvc_Action
{
    /**
     * @var array Request
     */
    protected $request = array();

    /**
     * @var array Response
     */
    protected $reponse = array(
        'title'       =>'',
        'description' =>'',
        'layout'      =>'', //empty default
    );

    /**
     * @var array
     */
    protected $data = array();

    /**
     * constructor
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Init
     */
    public function init()
    {
        
    }

    /**
     * Error page.
     * @param string $msg
     * @param array $data
     */
    public function error($msg = '', $data = array()) {
        $this->data['msg'] = $msg;
        $this->data['data'] = $data;
        $this->layout('Error');
    }

    /**
     * Json
     * @param array $data
     */
    public function json($data = array()) {
        //ob_start();
        echo json_encode($data);
        //header('Content-Length: '.ob_get_length());
        //ob_end_flush();
    }

    /**
    * @param string $layout
    */
    protected function layout($layout = 'normal') {
        //ob_start();
        $htmlPath = sprintf('%s/layout/%s.php',Smpe_Mvc_Bootstrap::$workingDir, $layout);
        $this->view($htmlPath);
        //header('Content-Length: '.ob_get_length());
        //ob_end_flush();
    }

    /**
     * Load view file.
     * @param string $htmlPath
     * @throws Exception
     */
    protected function view($htmlPath = '')
    {
        if(empty($htmlPath)) {
            $htmlPath = sprintf('%s/view/%s/%s_%s.php', Smpe_Mvc_Bootstrap::$workingDir, $this->request['module'], $this->request['controller'], $this->request['action']);
        }

        if(!is_file($htmlPath)){
            throw new Exception('Cannot load view file: '.$htmlPath);
        }

        include $htmlPath;
    }

    /**
     * @param string $message
     * @param mixed $data
     * @return array
     */
    protected function failed($message = 'Failed', $data = -1)
    {
        return array('data' => $data, 'msg' => $message);
    }

    /**
     * @param mixed $data
     * @param string $message
     * @return array
     */
    protected function succeed($data = '', $message = 'Succeed')
    {
        return array('data' => $data, 'msg' => $message);
    }

    /**
     * Initiates a transaction
     * @param string $moduleName
     * @throws Exception
     */
    protected function beginTransaction($moduleName = '')
    {
        $obj = $this->transactionObj($moduleName);
        if($obj->beginTransaction() === false){
            throw new Exception('Begin transaction error.');
        }
    }

    /**
     * Commits a transaction
     * @param string $moduleName
     * @throws Exception
     */
    protected function commit($moduleName = '')
    {
        $obj = $this->transactionObj($moduleName);
        if($obj->commit() === false){
            throw new Exception('Commit transaction error.');
        }
    }

    /**
     * Roll back a transaction
     * @param string $moduleName
     * @throws Exception
     */
    protected function rollBack($moduleName = '')
    {
        $obj = $this->transactionObj($moduleName);
        $obj->rollBack();
    }

    /**
     * @param string $moduleName
     * @return mixed
     * @throws Exception
     */
    private function transactionObj($moduleName = '') {
        if(empty($moduleName)) {
            $moduleName = $this->request['module'];
        }

        if(!isset(Config::$modules[$moduleName]['db']['type'])){
            throw new Exception('Module DB type error.');
        }

        $obj = array('Smpe_Db_'.Config::$modules[$moduleName]['db']['type'], 'db');
        return call_user_func_array($obj, array($moduleName));
    }
}