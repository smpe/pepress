<?php
class Smpe_Db_Sqlite implements Smpe_Db_Interface
{

    /**
     * @param $module
     * @return mixed
     * @throws Exception
     */
    public static function db($module)
    {
        // TODO: Implement db() method.
    }

    /**
     * @param $value
     * @return mixed
     */
    public function quote($value)
    {
        // TODO: Implement quote() method.
    }

    /**
     * @param $sql
     * @param array $parameters
     * @return mixed
     */
    public function query($sql, $parameters = array())
    {
        // TODO: Implement query() method.
    }

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
    public function fetchAll($fields = 'a.*', $join = '', $where = '1', $parameters = array(), $pageIndex = 0, $pageSize = 10000, $opts = array())
    {
        // TODO: Implement fetchAll() method.
    }

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $lock
     * @return mixed
     */
    public function all($filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = 0)
    {
        // TODO: Implement all() method.
    }

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
    public function lst($column = 'a.*', $filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = 0)
    {
        // TODO: Implement lst() method.
    }

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $lock
     * @return mixed
     */
    public function row($filter = array(), $group = '', $order = array(), $lock = 0)
    {
        // TODO: Implement row() method.
    }

    /**
     * @param string $column
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param int $lock
     * @return mixed
     */
    public function val($column = 'COUNT(*)', $filter = array(), $group = '', $order = array(), $lock = 0)
    {
        // TODO: Implement val() method.
    }

    /**
     * @param array $filter
     * @param string $group
     * @param array $order
     * @param string $lock
     * @return mixed
     */
    public function count($filter = array(), $group = '', $order = array(), $lock = '')
    {
        // TODO: Implement count() method.
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insert($data)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param $data
     * @param $filter
     * @return mixed
     */
    public function update($data, $filter)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $filter
     * @return mixed
     */
    public function delete($filter)
    {
        // TODO: Implement delete() method.
    }
}