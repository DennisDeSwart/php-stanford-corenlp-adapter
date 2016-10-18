<?php

/**
 * Require the CoreNLP class
 */
	require_once __DIR__.'/bootstrap.php';

/**
 * Demo usage
 */
	
	
	// instantiate the class
	$coreNLP 	= new Adapter();
	
	$text1	= 'John shouted and everybody waved'; // compounded sentence, will parse into 2 seperate sentences
	$coreNLP->getOutput($text1);
	
	// Second text
	$text2 		= 'The Golden Gate Bridge was designed by Joseph Strauss, an Engineer.'; 	// testing NER, regexNER
	$coreNLP->getOutput($text2);

/**
 * Display result
 */
	echo '<pre>';
	
	print_r($coreNLP->serverOutput);
	headerText('FIRST TEXT: Part-Of-Speech tree');
	print_r($coreNLP->trees[0]);

	headerText('FIRST TEXT: Annotators');
	print_r($coreNLP->annotators[0]);
	
	headerText('FIRST TEXT: Word IDs are combined with annotators');
	print_r($coreNLP->annotatorsWithTrees[0]);
	print_r($coreNLP->annotatorsWithTrees[1]);
	
	headerText('SECOND TEXT: Part-Of-Speech tree');
	print_r($coreNLP->trees[1]);

	// helper function for a nice header
	function headerText($header){
		echo '<br />***'.str_repeat('*', strlen($header)).'***<br />';
		echo '** '.$header.' **<br />';
		echo '***'.str_repeat('*', strlen($header)).'***<br /><br />';
	}
	
	