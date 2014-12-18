<?php
class Smpe_Mvc_Action
{
    /**
     * @var string
     */
    protected $workingDir = '';

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
     */
    public function __construct()
    {
        // Empty here.
    }

    /**
     * Init
     * @param $workingDir
     * @param $request
     */
    public function init($workingDir, $request)
    {
        $this->workingDir = $workingDir;
        $this->request = $request;
    }

    /**
     * Load
     */
    public function load() {

    }

    /**
     * Error
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
        $this->view(sprintf('%s/Layout/%s.php',$this->workingDir, $layout));
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
            $htmlPath = sprintf('%s/View/%s/%s_%s.php', $this->workingDir, $this->request['module'], $this->request['controller'], $this->request['action']);
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

        return call_user_func_array(array('Smpe_Db_'.Config::$modules[$moduleName]['db']['type'], 'db'), array($moduleName));
    }
}
