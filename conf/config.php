<?php
class config
{
	/**
	 * @var number 0:development 1:testing 2:staging 3:production
	 */
	public static $environment = 0;

	/**
	 * @var string Virtual directory.
	 */
	public static $vDir = '/pepress/public';

	/**
	 * @var bool url rewrite.
	 */
	public static $isRewrite = false;

	/**
	 * @var array modules
	 */
	public static $modules = array(
		'system' => array('data'=>'system'),
	);

	/**
	 * @var array DSN
	 */
	public static $db = array(
		'system' => array('server' => 'localhost', 'port' => 3306, 'user' => 'root', 'password' => '', 'database' => 'system', 'profiling' => false),
	);
}