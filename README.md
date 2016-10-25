
# PHP Stanford CoreNLP adapter

PHP adapter for use with Stanford CoreNLP tools 3.6.0
<br />
## Features
- PHP command line interface to the JAVA Stanford CoreNLP 3.6.0 server
- The package gets the following annotator data: Tokenize, Part-Of-Speech tagging, Lemma, NER, regexNER, OpenIE
- From the results, the package creates Part-Of-Speech Trees with depth, ID's and parentID's.
<br />

## Requirements
- PHP 5.3 or higher: it also works on PHP 7
- Windows or Linux
- cURL

```
  https://en.wikipedia.org/wiki/CURL
```
<br />

## Composer

You can install the adapter by putting the following line into your composer.json

```
    {
        "require": {
            "dennis-de-swart/php-stanford-corenlp-adapter": "*"
        }
    }
```

<br />
# Installation / Walkthrough
<br />

## Step 1: make sure you have installed the Stanford CoreNLP 3.6.0: 
```
http://stanfordnlp.github.io/CoreNLP/index.html#download
```
<br />
## Step 2: Server configuration and autoloader
The "Adapter" class needs to know your install configuration. An example of the configuration is included in the "bootstrap.php" file. Example:

```
define('CURLURL' , 'http://localhost:9000/');
define('CURLPROPERTIES' , '%22annotators%22%3A%22tokenize%2Cregexner%2Cparse%2Cpos%2Clemma%2Copenie%2Cner%22%2C%22prettyPrint%22%3A%22true%22');

require __DIR__.'/vendor/autoload.php';
```
So this is:
- The URL of the CoreNLP server. By default localhost:9000
- The annotator properties: don't change these unless you really need to.
- Starting the composer autoloader<br />

Now, you can either:<br />
a) include the above code into your main program OR<br />
b) include the "bootstrap.php" file into your main program:
```
require_once __DIR__.'/bootstrap.php';
```
<br />
## Step 3: Start the CoreNLP serve from the command line. 

```
java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000
```
You can change the port to 9001 if port 9000 is busy.

<br />
## Step 4: Test if the server has started by surfing to it's URL
```
http://localhost:9000/
```
When you surf to this URL, you should see the CoreNLP GUI. If you have problems with installation you can check the manual:
```
http://stanfordnlp.github.io/CoreNLP/corenlp-server.html
```
<br />
## Step 5: Instantiate the adapter:
```
$coreNLP 	= new Adapter();
```
<br />
## Step 6: To process a text, call the "getOutput" method:
```
 $text         = 'The Golden Gate Bridge was designed by Joseph Strauss.'; 
 $coreNLP->getOutput($text);
```

Note that the first time that you process a text, the server takes about 20 to 30 seconds extra to load definitions. All other calls to the server after that will be much faster. Small texts are usually processed within seconds.
<br /><br />
## Step 7: The results

If successful the following properties will be available:
```
 $coreNLP->serverMemory;      //contains all of the server output
 $coreNLP->trees;             //contains processed flat trees. Each part of the tree is assigned an ID key
 $coreNLP->getWordIDs($tree); //gets an array containing the words of that tree 
 ```
See index.php for a real world example
<br /><br /> 
## Any questions?

Please let me know. 
<br /><br />
## Credits

Some functions are forked from this "Stanford parser" package:
```
 https://github.com/agentile/PHP-Stanford-NLP
```

