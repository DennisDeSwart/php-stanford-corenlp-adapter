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
	define('CURLPROPERTIES' , '%22annotators%22%3A%22tokenize%2Cregexner%2Cparse%2Cpos%2Clemma%2Cner%22%2C%22outputFormat%22%3A%22text%22');


/**
 * Require the CoreNLP class
 */
	require_once ROOT_DIR.'/CoreNLP.php';


/**
 * Demo usage
 */

	// instantiate the class
	$coreNLP 	= new coreNLP();
	
	// First text
	$text1 		= 'The Golden Gate Bridge was designed by Joseph Strauss, an Engineer. It is located in San Francisco'; 	// testing NER, regexNER
	$coreNLP->getOutput($text1);
	

	// Optional: clear the tree ID's between pieces of text.
	$coreNLP->clearID();
		
	// Second text
	$text2 		= 'The quick brown fox jumped over the lazy dog';
	$coreNLP->getOutput($text2);

/**
 * Display result
 */

	echo '<pre>';
	headerText('FIRST TEXT: Part-Of-Speech trees');
	print_r($coreNLP->trees[0]);
	print_r($coreNLP->trees[1]);
	
	headerText('FIRST TEXT: Annotators');
	print_r($coreNLP->annotators[0]);
	print_r($coreNLP->annotators[1]);
	
	headerText('FIRST TEXT: Word IDs are combined with annotators');
	print_r($coreNLP->annotatorsWithTrees[0]);
	print_r($coreNLP->annotatorsWithTrees[1]);
	
	headerText('SECOND TEXT: Part-Of-Speech tree: note how the IDs have been reset with clearID()');
	print_r($coreNLP->trees[2]);

	// helper function for a nice header
	function headerText($header){
		echo '<br />***'.str_repeat('*', strlen($header)).'***<br />';
		echo '** '.$header.' **<br />';
		echo '***'.str_repeat('*', strlen($header)).'***<br /><br />';
	}
	
	