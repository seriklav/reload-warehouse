<?php
	//SESSION START
		session_start();
	// Error Reporting
		error_reporting(E_ALL);

	// Magic Quotes Fix
		if (ini_get('magic_quotes_gpc')) {
			function clean($data) {
				if (is_array($data)) {
					foreach ($data as $key => $value) {
						$data[clean($key)] = clean($value);
					}
				} else {
					$data = stripslashes($data);
				}

				return $data;
			}

			$_GET = clean($_GET);
			$_POST = clean($_POST);
			$_COOKIE = clean($_COOKIE);
		}

		if (!ini_get('date.timezone')) {
			date_default_timezone_set('UTC');
		}
	// CONFIG
		require_once ("config.php");
	// Autoloader
		function library($class) {
			$file = LIBRARY . str_replace('\\', '/', strtolower($class)) . '.php';

			if (is_file($file)) {
				include_once($file);
				return true;
			} else {
				return false;
			}
		}

		spl_autoload_register('library');
		spl_autoload_extensions('.php');

	// Registry
		$registry = new Registry();
		//echo "<pre>";print_r($action);exit;


	// LOADER
		$loader = new Loader($registry);
		$registry->set('load', $loader);

	// Database
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		$registry->set('db', $db);

	// Url
		$url = new Url(HOST, '');
		$registry->set('url', $url);

	// Log
		$log = new Log("error.log");
		$registry->set('log', $log);

	// Error Handler
		function error_handler($code, $message, $file, $line) {
			global $log;

			// error suppressed with @
			if (error_reporting() === 0) {
				return false;
			}

			switch ($code) {
				case E_NOTICE:
				case E_USER_NOTICE:
					$error = 'Notice';
					break;
				case E_WARNING:
				case E_USER_WARNING:
					$error = 'Warning';
					break;
				case E_ERROR:
				case E_USER_ERROR:
					$error = 'Fatal Error';
					break;
				default:
					$error = 'Unknown';
					break;
			}

			echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
			$log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);

			return true;
		}
	// Error Handler
		set_error_handler('error_handler');

	// Response
		$response = new Response();
		$response->addHeader('Content-Type: text/html; charset=utf-8');
		$response->setCompression(5);
		$registry->set('response', $response);

	// Front Controller
		$controller = new Front($registry);

	// Routing
		if (isset($_GET['route'])) {
			$action = new Action($_GET['route']);
		} else {
			$action = new Action('controller/home');
		}

	// Dispatch
		$controller->dispatch($action, new Action('controller/error'));

	// WRITE TO SCREEN
		$response->output();