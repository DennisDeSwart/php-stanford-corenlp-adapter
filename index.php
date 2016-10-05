<?php

/**
 * Log errors: Development purposes only
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * SET CONFIGURATION : CHANGE THIS TO YOUR OWN SETTINGS !!
 */

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'].'/php-stanford-corenlp-adapter/');
define('CURLURL' , 'http://localhost:9000/');
define('CURLPROPERTIES' , '%22tokenize.whitespace%22%3A%22true%22%2C%22annotators%22%3A%22tokenize%2Cparse%2Clemma%22%2C%22outputFormat%22%3A%22text%22');


/**
 * Require the CoreNLP class
 */
require_once ROOT_DIR.'/CoreNLP.php';


/**
 * Demo usage
 */

// init class
$coreNLP 	= new coreNLP();

// First sentence
$text 		= 'The quick brown fox jumped over the lazy dog';
$tree1 		= $coreNLP->getTree($text);

// Second sentence
$text 		= 'The quick brown fox jumped over another lazy dog';
$tree2 		= $coreNLP->getTree($text);

// Default behavior is that ID's will autoincrement for the next sentence. If you want to restart the ID count do: $coreNLP->clearID() between sentences

/**
 * Display result
 */
echo '<pre>';
echo '<br /><br />** Part-Of-Speech tree1 **<br /><br />';
print_r($tree1);
echo '<br /><br />** Part-Of-Speech tree2 **<br /><br />';
print_r($tree2);

