<?php
	$host = $_SERVER['HTTP_HOST'];
	$dir = dirname(__FILE__);

	// ENGINE
		define('HOST', 'http://'.$host.'/');

	// STYLES
		define('STYLES', 'http://' . $host . '/view/styles/');

	// ENGINE
		define('ENGINE', $dir . '/');

	// Library
		define('LIBRARY', $dir . '/library/');

	// VIEW
		define('VIEW', $dir . '/view/');

	// CONTROLLER
		define('CONTROLLER', $dir . '/controller/');

	// LOG
		define('LOGS', $dir . '/logs/');

	// DB
		define('DB_DRIVER', 'mysqli');
		define('DB_HOSTNAME', 'localhost');
		define('DB_USERNAME', 'root');
		define('DB_PASSWORD', 'root');
		define('DB_DATABASE', 'read_csv');
		define('DB_PORT', '3306');
		define('DB_PREFIX', '');