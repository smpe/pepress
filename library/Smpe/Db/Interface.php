<?php
interface Smpe_Db_Interface
{
    /**
     * @param $module
     * @return mixed
     * @throws Exception
     */
    public static function db($module);

    /**
     * @param $value
     * @return mixed
     */
    public function quote($value);

    /**
     * @param $sql
     * @param array $parameters
     * @return mixed
     */
    public function query($sql, $parameters = array());

    /**
     * @param string $fields
     * @param string $join
     * @param string $where
     * @param array $parameters
     * @param int $pageIndex
     * @param int $pageSize
     * @param array $opts
     * @return mixed
     */
    public function fetchAll($fields = 'a.*', $join = '', $where = '1', $parameters = array(), $pageIndex = 0, $pageSize = 10000, $opts = array());

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $lock
     * @return mixed
     */
    public function all($filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = 0);

    /**
     * @param string $column
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $lock
     * @return mixed
     */
    public function lst($column = 'a.*', $filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = 0);

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $lock
     * @return mixed
     */
    public function row($filter = array(), $group = '', $order = array(), $lock = 0);

    /**
     * @param string $column
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $lock
     * @return mixed
     */
    public function val($column = 'COUNT(*)', $filter = array(), $group = '', $order = array(), $lock = 0);

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param string $lock
     * @return mixed
     */
    public function count($filter = array(), $group = '', $order = array(), $lock = '');

    /**
     * @param $primaryId
     * @param int $lock
     * @return mixed
     */
    public function item($primaryId, $lock = 0);

    /**
     * @param $data
     * @return mixed
     */
    public function insert($data);

    /**
     * @param $data
     * @param $filter
     * @return mixed
     */
    public function update($data, $filter);

    /**
     * @param $filter
     * @return mixed
     */
    public function delete($filter);
}