<?php

/**
 * Log errors: Development purposes only
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


// set root
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'].'/php-stanford-corenlp-adapter/');
define('CURLURL'		, 'http://localhost:9001/');
define('CURLPROPERTIES'	, '%22tokenize.whitespace%22%3A%22true%22%2C%22annotators%22%3A%22tokenize%2Cparse%2Clemma%22%2C%22outputFormat%22%3A%22text%22');


/**
 * Require the CoreNLP class
 */
require_once ROOT_DIR.'/CoreNLP.php';

/**
 * Demo usage
 */

// First sentence
$coreNLP 			= new coreNLP();
$text 				= 'The quick brown fox jumped over the lazy dog';
$parse 				= $coreNLP->getParse($text);
$parsedText 		= $coreNLP->processParse($parse);
$getSentenceTree1 	= $coreNLP->getSentenceTree($parsedText); // creates tree from a parse

// Second sentence
$coreNLP->clearCounters(); // need to reset ID and depth counters.
$text 				= 'The quick brown fox jumped over another lazy dog';
$parse 				= $coreNLP->getParse($text);
$parsedText 		= $coreNLP->processParse($parse);
$getSentenceTree2 	= $coreNLP->getSentenceTree($parsedText); // creates tree from a parse

/**
 * Display result
 */
echo '<pre>'; 				// makes it easier to read
print_r($getSentenceTree1); // creates a tree with depth, ID's and parentID, foreach tag
print_r($getSentenceTree2);