<?php
class smpe_action
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
     */
    public function __construct()
    {
        // Empty here.
    }

    /**
     * @param $request
     */
    public function init($request)
    {
        $this->request = $request;
    }

    public function load() {

    }

    /**
    * @param string $layout
    */
    protected function layout($layout = 'normal')
    {
        ob_start();
        $this->view(sprintf('%s/views/%s.php',$this->request['working_dir'], $layout));
        header('Content-Length: '.ob_get_length());
        ob_end_flush();
    }

    /**
    * Load view file.
    * @param string $htmlPath
    */
    protected function view($htmlPath = '')
    {
        if(empty($htmlPath)) {
            $htmlPath = sprintf('%s/views/%s/%s_%s.php', $this->request['working_dir'], $this->request['module'], $this->request['controller'], $this->request['action']);
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
     * @throws Exception
     */
    protected function beginTransaction($module = '')
    {
        if(empty($module)) {
            $module = $this->request['module'];
        }

        if(smpe_mysql::db($module)->beginTransaction() === false){
            throw new Exception('事务启动失败');
        }

        smpe_mysql::$exception++;
    }

    /**
     * Commits a transaction
     * @throws Exception
     */
    protected function commit($module = '')
    {
        if(empty($module)) {
            $module = $this->request['module'];
        }

        if(smpe_mysql::db($module)->commit() === false){
            throw new Exception('事务提交失败');
        }

        smpe_mysql::$exception--;
    }

    /**
     * Roll back a transaction
     */
    protected function rollBack($module = '')
    {
        if(empty($module)) {
            $module = $this->request['module'];
        }

        smpe_mysql::db($module)->rollBack();

        smpe_mysql::$exception--;
    }
}
