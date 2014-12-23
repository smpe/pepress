<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.

class Config
{
	/**
	 * @var int version
	 */
	public static $version = 100;

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
		'Support' => array('dsn'=>'Support', 'domain'=>''),
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
