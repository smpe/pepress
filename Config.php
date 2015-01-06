<?php
class Config extends Smpe_Config
{
	/**
	 * @var array modules
	 */
	public static $modules = array(
		'Support' => array('dsn'=>'Support', 'listen'=>''),
	);

	/**
	 * @var array DSN
	 */
	public static $dsn = array(
		'Support' => array('type'=>'Mysql', 'server'=>'localhost', 'port'=>3306, 'user'=>'root', 'password'=>'', 'database'=>'support', 'profiling'=>false),
	);

	/**
	 * @var string Default module
	 */
	public static $defaultModule = 'Support';
}
