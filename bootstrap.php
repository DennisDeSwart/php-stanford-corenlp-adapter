<?php

/**
 * Log errors: Development purposes only.
 */
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

/**
 * Use the online API?
 */    
    define('ONLINE_API', TRUE);  // Set to FALSE if you want to use the offline Java version
     
 /**
  * Stanford API URL configuration
  */
    define('ONLINE_URL' , 'http://nlp.stanford.edu:8080/corenlp/process?outputFormat=json&Process=Submit&input='); // add url encoded text to the end
        
/**
 * Java version configuration
 */
    define('CURLURL' , 'http://localhost:9000/');
    define('CURLPROPERTIES' , '%22annotators%22%3A%22tokenize%2Cregexner%2Cparse%2Cpos%2Clemma%2Copenie%2Cner%22%2C%22prettyPrint%22%3A%22true%22');
    
    // Note: coref removed from properties since 4.0.0, because it seems to cause problems starting the server

/**
 * Start composer autoloader
 */
    require __DIR__.'/vendor/autoload.php';
