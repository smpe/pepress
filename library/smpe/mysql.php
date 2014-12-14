<?php
class smpe_mysql
{
    /**
     * @var string module name
     */
    private $module  = '';

    /**
     * @var string table name
     */
    private $table   = '';

    /**
     * @var string primary
     */
    private $primary = '';

    /**
     * @var array join tables
     */
    public $joins = array();

    /**
     * @var PDO database instance
     */
    private static $db = array();

    /**
     * @var int throw a PDOException and set its properties to reflect the error code and error information
     */
    public static $exceptions = 0;

    /**
     * @var int Query counter
     */
    public static $queries = 0;

    /**
     * @param $module
     * @return mixed
     * @throws Exception
     */
    public static function db($module)
    {
        //Allows multiple modules to use the same database
        $dataIndex = config::$modules[$module]['data'];

        if(empty(self::$db[$dataIndex])){
            $db = config::$db[$dataIndex];
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;', $db['server'], $db['port'], $db['database']);

            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            );

            try{
                //PDO::ATTR_EMULATE_PREPARES => 1
                self::$db[$dataIndex] = new PDO($dsn, $db['user'], $db['password'], $options);
                self::$db[$dataIndex]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Setting UTF-8 encoding, using GMT / UTC time zone
                //self::$db[$dataIndex]->exec("SET TIME_ZONE = '+00:00'");

                if($db['profiling']){ //profiling
                    self::$db[$dataIndex]->exec("SET profiling = 1");
                }
            } catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }

        return self::$db[$dataIndex];
    }

    /**
     * @param $module
     * @param $table
     * @param $primary
     */
    function __construct($module, $table, $primary)
    {
        $this->module  = $module;
        $this->table   = $module . '_' . $table;
        $this->primary = $primary;
    }

    /**
     * Quotes a string for use in a query.
     * @param string $value
     * @return string
     */
    public function quote($value)
    {
        return self::db($this->module)->quote($value);
    }

    /**
     * Run SQL
     * @param $sql
     * @param array $parameters
     * @return PDOStatement
     * @throws Exception
     */
    public function query($sql, $parameters = array())
    {
        self::$queries++;

        /*if(config::$db[config::$modules[$this->module]['data']]['profiling']){ //记录日志
            error_log(
                date('Y-m-d H:i:s').': '.$sql."\r\n".var_export($parameters, true)."\r\n\r\n",
                3,
                PROJECT_ROOT.'/log/err/mysql_'.monle_date_utc::today().'.log'
            );
        }*/

        try {
            $statement = self::db($this->module)->prepare($sql);
            $statement->execute($parameters);
            return $statement;
        } catch (PDOException $e) {
            //trigger_error($e->getMessage()."\r\n".$e->getTraceAsString()."\r\n".$sql."\r\n".var_export($parameters, true));
            if(self::$exceptions > 0){
                throw new Exception($e->getMessage());
            } else {
                return false;
            }
        }
    }

    /**
     * Read data, paging
     * @param string $fields
     * @param string $join
     * @param string $where Include where/group/having/order
     * @param array $parameters
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @param $fetchType
     * @return array|bool|string
     */
    public function fetchAll($fields = 'a.*', $join = '', $where = '1', $parameters = array(), $pageIndex = 0, $pageSize = 10000, $lock = '', $fetchType = PDO::FETCH_ASSOC)
    {
        $sql = sprintf("SELECT %s FROM `%s` a %s WHERE %s LIMIT %d, %d %s", $fields, $this->table, $join, $where, $pageIndex, $pageSize, $lock);
        return $this->query($sql, $parameters)->fetchAll($fetchType);
    }

    /**
     * Read the data, paging, sortable
     * @param array $filter array('user_id' => '2', 'gender' => 'male')
     * @param string $group
     * @param array $order array('a.user_id' => '0', 'b.gender' => '1')
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @return array|bool|string
     */
    public function all($filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = '')
    {
        $where = $this->where($filter, $group, $order);
        return $this->fetchAll('a.*', $where['join'], $where['where'], $where['param'], $pageIndex, $pageSize, $lock);
    }

    /**
     * Read the data, paging, sortable
     * @param string $column
     * @param array $filter array('user_id' => '2', 'gender' => 'male')
     * @param string $group
     * @param array $order array('a.user_id' => '0', 'b.gender' => '1')
     * @param int $pageIndex
     * @param int $pageSize
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @return array|bool|string
     */
    public function lst($column = 'a.*', $filter = array(), $group = '', $order = array(), $pageIndex = 0, $pageSize = 10000, $lock = '')
    {
        $where = $this->where($filter, $group, $order);
        return $this->fetchAll($column, $where['join'], $where['where'], $where['param'], $pageIndex, $pageSize, $lock);
    }

    /**
     * Read a single line, sortable
     * @param array $filter array('user_id' => '2', 'gender' => 'male')
     * @param string $group
     * @param array $order array('a.user_id' => '0', 'b.gender' => '1')
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @return array|bool|string
     */
    public function row($filter = array(), $group = '', $order = array(), $lock = '')
    {
        $where = $this->where($filter, $group, $order);
        $result = $this->fetchAll('a.*', $where['join'], $where['where'], $where['param'], 0, 1, $lock);
        return (empty($result) ? $result : $result[0]);
    }

    /**
     * @param string $column
     * @param array $filter array('user_id' => '2', 'gender' => 'male')
     * @param string $group
     * @param array $order array('a.user_id' => '0', 'b.gender' => '1')
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @return string
     */
    public function val($column = 'COUNT(*)', $filter = array(), $group = '', $order = array(), $lock = '')
    {
        $where = $this->where($filter, $group, $order);
        $result = $this->fetchAll($column, $where['join'], $where['where'], $where['param'], 0, 1, $lock, PDO::FETCH_NUM);
        return (empty($result) ? '' : $result[0][0]);
    }

    /**
     * Total read row, sortable
     * @param array $filter array('user_id' => '2', 'gender' => 'male')
     * @param string $group
     * @param array $order array('a.user_id' => '0', 'b.gender' => '1')
     * @param string $lock Empty, FOR UPDATE , LOCK IN SHARE MODE
     * @return int
     */
    public function count($filter = array(), $group = '', $order = array(), $lock = '')
    {
        if(empty($group)){
            $count = $this->val('COUNT(*)', $filter, $group, $order, $lock);
            return (int)$count;
        }
        else{
            //TODO: 含用group by的条件的行数计算, 暂时拷贝fetchAll()方法
            $where = $this->where($filter, $group, $order);

            $sql = sprintf("SELECT COUNT(*) FROM (SELECT 1 FROM `%s` a %s WHERE %s %s) b", $this->table, $where['join'], $where['where'], $lock);

            $result = $this->query($sql, $where['param'])->fetchAll(PDO::FETCH_NUM);

            return (empty($result) ? 0 : (int)$result[0][0]);
        }
    }

    /**
     * Read a single line, according to the primary key
     * @param string $primaryId
     * @return array
     */
    public function item($primaryId)
    {
        $where = $this->where(array($this->primary => $primaryId), '', false);
        $result = $this->fetchAll('a.*', $where['join'], $where['where'], $where['param']);
        return (empty($result) ? $result : $result[0]);
    }

    /**
     * Added (single, multi-line)
     * @param mixed $data
     * @return number
     */
    public function insert($data)
    {
        if(empty($data)) {
            return 0;
        }

        if(!isset($data[0])) {
            $data = array($data); //Support insert multiple rows
        }

        $sql = sprintf('INSERT INTO `%s`(`%s`) VALUES %s', $this->table, implode('`,`', array_keys($data[0])), $this->insertFields($data));

        if($this->query($sql)->rowCount() > 0){
            return self::db($this->module)->lastInsertId();
        } else {
            return 0;
        }
    }

    /**
     * update
     * @param mixed $data
     * @param mixed $filter
     * @return number
     */
    public function update($data, $filter)
    {
        $where = $this->where($filter, '', false);
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $this->table, $this->updateFields($data), $where['where']);
        return $this->query($sql, $where['param'])->rowCount();
    }

    /**
     * delete
     * @param mixed $filter
     * @return number
     */
    public function delete($filter)
    {
        $where = $this->where($filter, '', false);
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $this->table, $where['where']);
        return $this->query($sql, $where['param'])->rowCount();
    }

    /**
     * Build Conditions
     * @param array $filter
     * @param string $group
     * @param array $order
     * @return array
     */
    public function where($filter, $group = '', $order = array())
    {
        $result = array('join_tables' => array(), 'join' => '', 'where' => ' 1 ', 'param' => array());

        /*
            Advanced Search
            $filter = array(
                array('AND', 'a', 'field_a', '=',     monle_filter::datetime('field1', INPUT_GET), true),
                array('OR',  'a', 'field_b', '<',     monle_filter::datetime('field1', INPUT_GET)),
                array('AND', 'a', 'field_c', 'RLIKE', monle_filter::datetime('field1', INPUT_GET)),
                array('AND', 'a', 'field_d', 'IN',    array('1', '2')),
                array('AND', '',  '',        '(',     array( //复合条件
                    array('AND', 'a', 'field_e', '=',     monle_filter::datetime('field1', INPUT_GET)),
                )),
            );
        */

        /*
            Simple search
            $filter = array('user_id' => '2', 'gender' => 'male');
        */

        if(isset($filter[0])){ //Advanced Search
            foreach($filter as $item){
                $this->whereItem($result, $item);
            }

            //JOIN
            foreach ($result['join_tables'] as $alias) {
                if(isset($this->joins[$alias])) {
                    $result['join'] .= $this->joins[$alias];
                }
            }
        }
        else{ //Simple search
            foreach($filter as $key => $value){
                $result['where'] .= sprintf(" AND `%s` = :%s ", $key, $key);
                $result['param'][$key] = $value;
            }
        }


        //Group by
        if($group != ''){
            $result['where'] .= ' GROUP BY '.$group;
        }

        // Sort
        // false: none,
        // array(): Primary key reverse,
        // array('id'=>'asc','str'=>'desc'): Specify the sort
        if(is_array($order)){
            $result['where'] .= ' ORDER BY ';

            if(count($order) > 0){
                foreach($order as $key => $value){
                    //将形如a.user_id拆分为$tableAlias和$field
                    $fields = explode('.', $key);
                    if(count($fields) == 1){
                        $tableAlias = 'a';
                        $field = $fields[0];
                    }
                    else{
                        $tableAlias = $fields[0];
                        $field = $fields[1];
                    }

                    $keys = $this->quoteField($tableAlias, $field, $result['param']);
                    $result['where'] .= $keys['field'].' '.self::$orderBy[$value].' ,';
                }

                $result['where'] = rtrim($result['where'], ',');
            }
            else{
                $result['where'] .= ' a.'.$this->primary.' DESC ';
            }
        }

        return $result;
    }

    /**
     * 构建单个条件
     * @param array $result
     * @param array $item
     */
    private function whereItem(&$result, $item)
    {
        //personal_message, money_transaction中有复合查询的应用例子

        /*
         $item[0] 逻辑连接条件, 例如: AND,OR
         $item[1] 表的别名, 例如: a
         $item[2] 字段, 例如: a.user_id
         $item[3] 比较条件, 例如: =,LIKE
         $item[4] 值
         $item[5] 是否允许忽略此字段, true:允许, false:禁止
         */

        if(is_null($item[4]) || $item[4] === false || (isset($item[5]) && $item[5] === true && $item[4] == '')){
            return;
        }

        $keys = $this->quoteField($item[1], $item[2], $result['param']);

        //将非空或非a表保存到$result['join_tables']
        if($item[1] != '' && $item[1] != 'a'){
            $result['join_tables'] = array_merge($result['join_tables'], array($item[1]));
        }

        switch($item[3]){
            case     '(': //复合条件
                $result['where'] .= $item[0].' ( 1 ';
                foreach($item[4] as $subItem){
                    $this->whereItem($result, $subItem);
                }
                $result['where'] .= ' ) ';
                break;
            case    'IN': //范围
                $result['where'] .= sprintf(" %s %s IN ( ", $item[0], $keys['field']);
                for($i = 0; $i < count($item[4]); $i++){
                    $result['where'] .= ($i > 0 ? ',' : '') . self::db($this->module)->quote($item[4][$i]);
                }
                $result['where'] .= ' ) ';
                break;
            case  'LIKE': //全模糊
                $result['where'] .= sprintf(" %s %s LIKE '%s' ", $item[0], $keys['field'], '%'.trim($this->quote($item[4]), '\'').'%');
                break;
            case 'LLIKE': //左模糊
                $result['where'] .= sprintf(" %s %s LIKE '%s' ", $item[0], $keys['field'], '%'.trim($this->quote($item[4]), '\''));
                break;
            case 'RLIKE': //右模糊
                $result['where'] .= sprintf(" %s %s LIKE '%s' ", $item[0], $keys['field'], trim($this->quote($item[4]), '\'').'%');
                break;
            case     '<':
            case    '<=':
                $result['where'] .= sprintf(" %s %s %s :%s ", $item[0], $keys['field'], $item[3], $keys['param'].'_max');
                $result['param'][$keys['param'].'_max'] = $item[4];
                break;
            case     '>':
            case    '>=':
                $result['where'] .= sprintf(" %s %s %s :%s ", $item[0], $keys['field'], $item[3], $keys['param'].'_min');
                $result['param'][$keys['param'].'_min'] = $item[4];
                break;
            case     '=':
            case    '<>':
            default:      //全等于/不等于
                $result['where'] .= sprintf(" %s %s %s :%s ", $item[0], $keys['field'], $item[3], $keys['param']);
                $result['param'][$keys['param']] = $item[4];
                break;
        }
    }

    /**
     * 对字段加``
     * @param $tableAlias
     * @param $field
     * @param $params
     * @return array
     */
    private function quoteField($tableAlias, $field, $params)
    {
        //当$tableAlias为空值时, 忽略掉
        $fullField = empty($tableAlias) ? '`'.$field.'`' : $tableAlias.'.`'.$field.'`';

        $result = array('field'=>$fullField, 'param'=>$field);

        if(isset($params[$result['param']])){ //避免重复
            $result['param'] = $result['param'].'_'.rand(10000, 99999);
        }

        return $result;
    }

    /**
     * Generate set fields of sql statement for update
     * @param $values
     * @return string
     */
    private function updateFields($values)
    {
        $fields = '';

        foreach ($values as $key => $value) {
            $fields .= sprintf(',`%s` = %s', $key, $this->quote($value));
        }

        return substr($fields, 1);
    }

    /**
     * Generate multi value of sql statement for insert.
     * @param $values
     * @return string
     */
    private function insertFields($values)
    {
        $sql = '';

        foreach ($values as $value) {
            $a = '';

            foreach ($value as $v) {
                $a .= sprintf('%s,', $this->quote($v));
            }

            $sql .= sprintf('(%s),', rtrim($a, ','));
        }

        return rtrim($sql, ',');
    }
}
