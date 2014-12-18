<?php
class Config
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
		'Support' => array('db'=>'Support'),
	);

	/**
	 * @var array DSN
	 */
	public static $db = array(
		'Support' => array('type'=>'Mysql', 'server'=>'localhost', 'port'=>3306, 'user'=>'root', 'password'=>'', 'database'=>'system', 'profiling'=>false),
	);

	/**
	 * @var string Default module
	 */
	public static $defaultModule = 'Support';
}
