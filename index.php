<?php

/**
 * Important notes:
 * - To use the online API  : set ONLINE_API to TRUE
 * - To use Java CoreNLP    : set ONLINE_API to FALSE
 * 
 * - ONLINE_API is set to FALSE by default. You can change this setting in "bootstrap.php"
 * - OpenIE annotator is only available on the Java version
 */

    require_once __DIR__.'/bootstrap.php';  // bootstrap also contains the config
	
/**
 * Demo usage
 */
	
    // instantiate the class
    $coreNLP 	= new CorenlpAdapter();

    $text1 = 'I will meet Mary in New York at 10pm';
    $coreNLP->getOutput($text1);

    // Second text
    $text2 = 'The Golden Gate Bridge was designed by Joseph Strauss.';
    $coreNLP->getOutput($text2);
    
/**
 * Display result
 */
	
    // this makes it easier to read
    echo '<pre>';

    // show complete output
    headerText('The "Server Memory Object" (below) contains all the server output');
    print_r($coreNLP->serverMemory);

    // first text tree
    headerText('FIRST TEXT: Part-Of-Speech tree');
    print_r($coreNLP->trees[0]);

    // second text tree
    headerText('SECOND TEXT: Part-Of-Speech tree');
    print_r($coreNLP->trees[1]);
    
    // get IDs for a tree
    headerText('EVERY TREE HAS UNIQUE IDs: this shows the Word-tree-IDs for the second tree');
    print_r($coreNLP->getWordValues($coreNLP->trees[1]));

    // this is just a helper function for a nice header
    function headerText($header){
            echo '<br />***'.str_repeat('*', strlen($header)).'***<br />';
            echo '** '.$header.' **<br />';
            echo '***'.str_repeat('*', strlen($header)).'***<br /><br />';
    }
	
	