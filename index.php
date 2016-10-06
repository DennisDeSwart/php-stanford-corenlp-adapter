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
	$text1 		= 'The Golden Gate Bridge was designed by Joseph Strauss'; 	// showing off NER
	$tree1 		 = $coreNLP->getTree($text1, true);							// true prints the tree
	
	$annotators1 		= $coreNLP->getTextAnnotators($text1);
	$wordIDsAnnotators1 = $coreNLP->combineWordIDsAnnotators($tree1, $annotators1);
	
	// Optional: clear the tree ID's. Default is to autoincrement tree ID's unless a new CoreNLP object is made
	//$coreNLP->clearID();
		
	// Second sentence
	$text2 		= 'The quick brown fox jumped over the lazy dog';
	$tree2 		= $coreNLP->getTree($text2);


/**
 * Display result
 */

	echo '<pre>';
	headerText('Part-Of-Speech tree1');
	print_r($tree1);
	
	headerText('Word IDs + Annotators for tree1: pay attention to the good NER info');
	print_r($wordIDsAnnotators1);
	
	headerText('Part-Of-Speech tree2: pay attention to the IDs resuming from tree1');
	print_r($tree2);

	// helper function for a nice header
	function headerText($header){
		echo '<br />***'.str_repeat('*', strlen($header)).'***<br />';
		echo '** '.$header.' **<br />';
		echo '***'.str_repeat('*', strlen($header)).'***<br /><br />';
	}
	
	