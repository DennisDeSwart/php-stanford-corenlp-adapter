
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


***********************************************************************
### Step 7a: The ServerMemory contains all the server data
***********************************************************************
```
Array
(
    [0] => stdClass Object
        (
            [sentences] => Array
                (
                    [0] => stdClass Object
                        (
                            [index] => 0
                            [parse] => (ROOT
  (S
    (NP (DT The) (NNP Golden) (NNP Gate) (NNP Bridge))
    (VP (VBD was)
      (VP (VBN designed)
        (PP (IN by)
          (NP (NNP Joseph) (NNP Strauss)))))
    (. .)))
                            [basic-dependencies] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => stdClass Object
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => stdClass Object
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => stdClass Object
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => stdClass Object
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => stdClass Object
                                        (
                                            [dep] => nmod
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                    [9] => stdClass Object
                                        (
                                            [dep] => punct
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 10
                                            [dependentGloss] => .
                                        )

                                )

                            [collapsed-dependencies] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => stdClass Object
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => stdClass Object
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => stdClass Object
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => stdClass Object
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => stdClass Object
                                        (
                                            [dep] => nmod:agent
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                )

                            [collapsed-ccprocessed-dependencies] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => stdClass Object
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => stdClass Object
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => stdClass Object
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => stdClass Object
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => stdClass Object
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => stdClass Object
                                        (
                                            [dep] => nmod:agent
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                    [9] => stdClass Object
                                        (
                                            [dep] => punct
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 10
                                            [dependentGloss] => .
                                        )

                                )

                            [openie] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [subject] => Golden Gate Bridge
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 4
                                                )

                                            [relation] => was
                                            [relationSpan] => Array
                                                (
                                                    [0] => 4
                                                    [1] => 5
                                                )

                                            [object] => designed
                                            [objectSpan] => Array
                                                (
                                                    [0] => 5
                                                    [1] => 6
                                                )

                                        )

                                    [1] => stdClass Object
                                        (
                                            [subject] => Golden Gate Bridge
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 4
                                                )

                                            [relation] => was designed by
                                            [relationSpan] => Array
                                                (
                                                    [0] => 4
                                                    [1] => 6
                                                )

                                            [object] => Joseph Strauss
                                            [objectSpan] => Array
                                                (
                                                    [0] => 7
                                                    [1] => 9
                                                )

                                        )

                                )

                            [tokens] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [index] => 1
                                            [word] => The
                                            [originalText] => The
                                            [lemma] => the
                                            [characterOffsetBegin] => 0
                                            [characterOffsetEnd] => 3
                                            [pos] => DT
                                            [ner] => O
                                            [before] => 
                                            [after] =>  
                                        )

                                    [1] => stdClass Object
                                        (
                                            [index] => 2
                                            [word] => Golden
                                            [originalText] => Golden
                                            [lemma] => Golden
                                            [characterOffsetBegin] => 4
                                            [characterOffsetEnd] => 10
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [2] => stdClass Object
                                        (
                                            [index] => 3
                                            [word] => Gate
                                            [originalText] => Gate
                                            [lemma] => Gate
                                            [characterOffsetBegin] => 11
                                            [characterOffsetEnd] => 15
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [3] => stdClass Object
                                        (
                                            [index] => 4
                                            [word] => Bridge
                                            [originalText] => Bridge
                                            [lemma] => Bridge
                                            [characterOffsetBegin] => 16
                                            [characterOffsetEnd] => 22
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [4] => stdClass Object
                                        (
                                            [index] => 5
                                            [word] => was
                                            [originalText] => was
                                            [lemma] => be
                                            [characterOffsetBegin] => 23
                                            [characterOffsetEnd] => 26
                                            [pos] => VBD
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [5] => stdClass Object
                                        (
                                            [index] => 6
                                            [word] => designed
                                            [originalText] => designed
                                            [lemma] => design
                                            [characterOffsetBegin] => 27
                                            [characterOffsetEnd] => 35
                                            [pos] => VBN
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [6] => stdClass Object
                                        (
                                            [index] => 7
                                            [word] => by
                                            [originalText] => by
                                            [lemma] => by
                                            [characterOffsetBegin] => 36
                                            [characterOffsetEnd] => 38
                                            [pos] => IN
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [7] => stdClass Object
                                        (
                                            [index] => 8
                                            [word] => Joseph
                                            [originalText] => Joseph
                                            [lemma] => Joseph
                                            [characterOffsetBegin] => 39
                                            [characterOffsetEnd] => 45
                                            [pos] => NNP
                                            [ner] => PERSON
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [8] => stdClass Object
                                        (
                                            [index] => 9
                                            [word] => Strauss
                                            [originalText] => Strauss
                                            [lemma] => Strauss
                                            [characterOffsetBegin] => 46
                                            [characterOffsetEnd] => 53
                                            [pos] => NNP
                                            [ner] => PERSON
                                            [before] =>  
                                            [after] => 
                                        )

                                    [9] => stdClass Object
                                        (
                                            [index] => 10
                                            [word] => .
                                            [originalText] => .
                                            [lemma] => .
                                            [characterOffsetBegin] => 53
                                            [characterOffsetEnd] => 54
                                            [pos] => .
                                            [ner] => O
                                            [before] => 
                                            [after] => 
                                        )

                                )

                        )

                )

        )

)
 ```
*************************
### Step 7b: The Tree
*************************
 ```
Array
(
    [1] => Array
        (
            [parent] => 
            [pennTreebankTag] => ROOT
            [depth] => 0
        )

    [2] => Array
        (
            [parent] => 
            [pennTreebankTag] => ROOT
            [depth] => 0
        )

    [3] => Array
        (
            [parent] => 2
            [pennTreebankTag] => S
            [depth] => 2
        )

    [4] => Array
        (
            [parent] => 3
            [pennTreebankTag] => NP
            [depth] => 4
        )

    [5] => Array
        (
            [parent] => 4
            [pennTreebankTag] => DT
            [depth] => 6
            [word] => The
        )

    [6] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Golden
        )

    [7] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Gate
        )

    [8] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Bridge
        )

    [9] => Array
        (
            [parent] => 3
            [pennTreebankTag] => VP
            [depth] => 4
        )

    [10] => Array
        (
            [parent] => 9
            [pennTreebankTag] => VBD
            [depth] => 6
            [word] => was
        )

    [11] => Array
        (
            [parent] => 9
            [pennTreebankTag] => VP
            [depth] => 6
        )

    [12] => Array
        (
            [parent] => 11
            [pennTreebankTag] => VBN
            [depth] => 8
            [word] => designed
        )

    [13] => Array
        (
            [parent] => 11
            [pennTreebankTag] => PP
            [depth] => 8
        )

    [14] => Array
        (
            [parent] => 13
            [pennTreebankTag] => IN
            [depth] => 10
            [word] => by
        )

    [15] => Array
        (
            [parent] => 13
            [pennTreebankTag] => NP
            [depth] => 10
        )

    [16] => Array
        (
            [parent] => 15
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Joseph
        )

    [17] => Array
        (
            [parent] => 15
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Strauss
        )

    [18] => Array
        (
            [parent] => 3
            [pennTreebankTag] => .
            [depth] => 4
            [word] => .
        )

)

 ```
***************************************************************************
### Part 7c: the WordIDs array, which contains just the words
***************************************************************************
```


Array
(
    [5] => Array
        (
            [parent] => 4
            [pennTreebankTag] => DT
            [depth] => 6
            [word] => The
        )

    [6] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Golden
        )

    [7] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Gate
        )

    [8] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Bridge
        )

    [10] => Array
        (
            [parent] => 9
            [pennTreebankTag] => VBD
            [depth] => 6
            [word] => was
        )

    [12] => Array
        (
            [parent] => 11
            [pennTreebankTag] => VBN
            [depth] => 8
            [word] => designed
        )

    [14] => Array
        (
            [parent] => 13
            [pennTreebankTag] => IN
            [depth] => 10
            [word] => by
        )

    [16] => Array
        (
            [parent] => 15
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Joseph
        )

    [17] => Array
        (
            [parent] => 15
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Strauss
        )

    [18] => Array
        (
            [parent] => 3
            [pennTreebankTag] => .
            [depth] => 4
            [word] => .
        )

)
 ```

## Any questions?

Please let me know. 
<br /><br />
## Credits

Some functions are forked from this "Stanford parser" package:
```
 https://github.com/agentile/PHP-Stanford-NLP
```

