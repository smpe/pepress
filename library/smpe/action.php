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
        'layout'      =>'', //默认为空值
    );

    /**
     * @var array  页面动态数据: 子类中赋值
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
     * 初始化$req参数
     * @param $request
     * @param $isRun
     * @return array
     */
    public function init($request)
    {
        $this->request = $request;

        return call_user_func_array(array($this, $request['action']), $request['args']);
    }

    /**
    * 显示view, 从layout开始
    * @param string $layout normal blank empty app_money
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
            $htmlPath = sprintf('%s/views/%s/%s_%s.php', $this->request['working_dir'], $this->req['module'], $this->req['controller'], $this->req['action']);
        }
        if(!is_file($htmlPath)){
            throw new Exception('Cannot load view file: '.$htmlPath);
        }

        include $htmlPath;
    }

    /**
     * 构建请求失败返回数组
     * @param string $message
     * @param mixed $data
     * @return array
     */
    protected function failed($message = 'Failed', $data = -1)
    {
        return array('data' => $data, 'msg' => $message);
    }

    /**
     * 构建请求成功返回数组
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
