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
	define('CURLPROPERTIES' , '%22tokenize.whitespace%22%3A%22true%22%2C%22annotators%22%3A%22tokenize%2Cner%2Cdcoref%2Clemma%22%2C%22outputFormat%22%3A%22text%22');


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
	
	// Optional: reset the tree ID's
	$coreNLP->clearID();
	
	// Second sentence
	$text 		= 'The Golden Gate Bridge was designed by Joseph Strauss'; // showing off NER
	$tree2 		= $coreNLP->getTree($text);


/**
 * Display result
 */

	// show a nice header
	function headerText($header){
		echo '<br />***'.str_repeat('*', strlen($header)).'***<br />';
		echo '** '.$header.' **<br />';
		echo '***'.str_repeat('*', strlen($header)).'***<br /><br />';
	}
	
	echo '<pre>';
	headerText('Part-Of-Speech tree1');
	print_r($tree1);
	headerText('Part-Of-Speech tree2');
	print_r($tree2);

