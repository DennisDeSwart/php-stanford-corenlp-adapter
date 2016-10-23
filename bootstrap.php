<?php

/**
 * Log errors: Development purposes only
 */
	error_reporting(E_ALL);
	ini_set('display_errors', 1);


/**
 * SET CONFIGURATION : CHANGE THIS TO YOUR OWN SETTINGS !!
 */
	define('CURLURL' , 'http://localhost:9000/');
	define('CURLPROPERTIES' , '%22annotators%22%3A%22tokenize%2Cregexner%2Cparse%2Cpos%2Clemma%2Copenie%2Cner%22%2C%22prettyPrint%22%3A%22true%22');

/**
 * define basic variables
 */
$classesDir = array (
		__DIR__.'/src/CoreNLP/',
		__DIR__.'/src/Tree/',
);

/**
 * Start AIP's own autoloader
 */
function autoloader($class_name) {

	global $classesDir;
	foreach ($classesDir as $directory) {
		if (file_exists($directory . $class_name . '.php')) {
			require_once ($directory . $class_name . '.php');
			return;
		}
	}
}
spl_autoload_register('autoloader');

/**
 * Start composer autoloader
 */
require __DIR__.'/vendor/autoload.php';
