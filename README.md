
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
### Diagram 7a: The ServerMemory contains all the server data
***********************************************************************
```
[1] => Array
        (
            [sentences] => Array
                (
                    [0] => Array
                        (
                            [index] => 0
                            [parse] => (ROOT
  (S
    (NP (DT The) (NNP Golden) (NNP Gate) (NNP Bridge))
    (VP (VBD was)
      (VP (VBN designed)
        (PP (IN by)
          (NP
            (NP (NNP Joseph) (NNP Strauss))
            (, ,)
            (NP (DT an) (NN Engineer))))))
    (. .)))
                            [basic-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => Array
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => Array
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => Array
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                    [9] => Array
                                        (
                                            [dep] => punct
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 10
                                            [dependentGloss] => ,
                                        )

                                    [10] => Array
                                        (
                                            [dep] => det
                                            [governor] => 12
                                            [governorGloss] => Engineer
                                            [dependent] => 11
                                            [dependentGloss] => an
                                        )

                                    [11] => Array
                                        (
                                            [dep] => appos
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 12
                                            [dependentGloss] => Engineer
                                        )

                                    [12] => Array
                                        (
                                            [dep] => punct
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 13
                                            [dependentGloss] => .
                                        )

                                )

                            [collapsed-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => Array
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => Array
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => Array
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod:agent
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                    [9] => Array
                                        (
                                            [dep] => det
                                            [governor] => 12
                                            [governorGloss] => Engineer
                                            [dependent] => 11
                                            [dependentGloss] => an
                                        )

                                    [10] => Array
                                        (
                                            [dep] => appos
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 12
                                            [dependentGloss] => Engineer
                                        )

                                )

                            [collapsed-ccprocessed-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 6
                                            [dependentGloss] => designed
                                        )

                                    [1] => Array
                                        (
                                            [dep] => det
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 1
                                            [dependentGloss] => The
                                        )

                                    [2] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 2
                                            [dependentGloss] => Golden
                                        )

                                    [3] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 4
                                            [governorGloss] => Bridge
                                            [dependent] => 3
                                            [dependentGloss] => Gate
                                        )

                                    [4] => Array
                                        (
                                            [dep] => nsubjpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 4
                                            [dependentGloss] => Bridge
                                        )

                                    [5] => Array
                                        (
                                            [dep] => auxpass
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 5
                                            [dependentGloss] => was
                                        )

                                    [6] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 7
                                            [dependentGloss] => by
                                        )

                                    [7] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 8
                                            [dependentGloss] => Joseph
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod:agent
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 9
                                            [dependentGloss] => Strauss
                                        )

                                    [9] => Array
                                        (
                                            [dep] => punct
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 10
                                            [dependentGloss] => ,
                                        )

                                    [10] => Array
                                        (
                                            [dep] => det
                                            [governor] => 12
                                            [governorGloss] => Engineer
                                            [dependent] => 11
                                            [dependentGloss] => an
                                        )

                                    [11] => Array
                                        (
                                            [dep] => appos
                                            [governor] => 9
                                            [governorGloss] => Strauss
                                            [dependent] => 12
                                            [dependentGloss] => Engineer
                                        )

                                    [12] => Array
                                        (
                                            [dep] => punct
                                            [governor] => 6
                                            [governorGloss] => designed
                                            [dependent] => 13
                                            [dependentGloss] => .
                                        )

                                )

                            [openie] => Array
                                (
                                    [0] => Array
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

                                    [1] => Array
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

                                            [object] => Engineer
                                            [objectSpan] => Array
                                                (
                                                    [0] => 11
                                                    [1] => 12
                                                )

                                        )

                                    [2] => Array
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
                                    [0] => Array
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

                                    [1] => Array
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

                                    [2] => Array
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

                                    [3] => Array
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

                                    [4] => Array
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

                                    [5] => Array
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

                                    [6] => Array
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

                                    [7] => Array
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

                                    [8] => Array
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

                                    [9] => Array
                                        (
                                            [index] => 10
                                            [word] => ,
                                            [originalText] => ,
                                            [lemma] => ,
                                            [characterOffsetBegin] => 53
                                            [characterOffsetEnd] => 54
                                            [pos] => ,
                                            [ner] => O
                                            [before] => 
                                            [after] =>  
                                        )

                                    [10] => Array
                                        (
                                            [index] => 11
                                            [word] => an
                                            [originalText] => an
                                            [lemma] => a
                                            [characterOffsetBegin] => 55
                                            [characterOffsetEnd] => 57
                                            [pos] => DT
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [11] => Array
                                        (
                                            [index] => 12
                                            [word] => Engineer
                                            [originalText] => Engineer
                                            [lemma] => engineer
                                            [characterOffsetBegin] => 58
                                            [characterOffsetEnd] => 66
                                            [pos] => NN
                                            [ner] => TITLE
                                            [before] =>  
                                            [after] => 
                                        )

                                    [12] => Array
                                        (
                                            [index] => 13
                                            [word] => .
                                            [originalText] => .
                                            [lemma] => .
                                            [characterOffsetBegin] => 66
                                            [characterOffsetEnd] => 67
                                            [pos] => .
                                            [ner] => O
                                            [before] => 
                                            [after] => 
                                        )

                                )

                        )

                )

        )
 ```
********************************
### Diagram 7b: Tree With Tokens
********************************
 ```
Array
(
    [1] => Array
        (
            [parent] => 0
            [pennTreebankTag] => ROOT
            [depth] => 0
        )

    [2] => Array
        (
            [parent] => 0
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
            [index] => 1
            [originalText] => The
            [lemma] => the
            [characterOffsetBegin] => 0
            [characterOffsetEnd] => 3
            [pos] => DT
            [ner] => O
            [before] => 
            [after] =>  
        )

    [6] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Golden
            [index] => 2
            [originalText] => Golden
            [lemma] => Golden
            [characterOffsetBegin] => 4
            [characterOffsetEnd] => 10
            [pos] => NNP
            [ner] => LOCATION
            [before] =>  
            [after] =>  
        )

    [7] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Gate
            [index] => 3
            [originalText] => Gate
            [lemma] => Gate
            [characterOffsetBegin] => 11
            [characterOffsetEnd] => 15
            [pos] => NNP
            [ner] => LOCATION
            [before] =>  
            [after] =>  
        )

    [8] => Array
        (
            [parent] => 4
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Bridge
            [index] => 4
            [originalText] => Bridge
            [lemma] => Bridge
            [characterOffsetBegin] => 16
            [characterOffsetEnd] => 22
            [pos] => NNP
            [ner] => LOCATION
            [before] =>  
            [after] =>  
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
            [index] => 5
            [originalText] => was
            [lemma] => be
            [characterOffsetBegin] => 23
            [characterOffsetEnd] => 26
            [pos] => VBD
            [ner] => O
            [before] =>  
            [after] =>  
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
            [index] => 6
            [originalText] => designed
            [lemma] => design
            [characterOffsetBegin] => 27
            [characterOffsetEnd] => 35
            [pos] => VBN
            [ner] => O
            [before] =>  
            [after] =>  
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
            [index] => 7
            [originalText] => by
            [lemma] => by
            [characterOffsetBegin] => 36
            [characterOffsetEnd] => 38
            [pos] => IN
            [ner] => O
            [before] =>  
            [after] =>  
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
            [pennTreebankTag] => NP
            [depth] => 12
        )

    [17] => Array
        (
            [parent] => 16
            [pennTreebankTag] => NNP
            [depth] => 14
            [word] => Joseph
            [index] => 8
            [originalText] => Joseph
            [lemma] => Joseph
            [characterOffsetBegin] => 39
            [characterOffsetEnd] => 45
            [pos] => NNP
            [ner] => PERSON
            [before] =>  
            [after] =>  
        )

    [18] => Array
        (
            [parent] => 16
            [pennTreebankTag] => NNP
            [depth] => 14
            [word] => Strauss
            [index] => 9
            [originalText] => Strauss
            [lemma] => Strauss
            [characterOffsetBegin] => 46
            [characterOffsetEnd] => 53
            [pos] => NNP
            [ner] => PERSON
            [before] =>  
            [after] => 
        )

    [19] => Array
        (
            [parent] => 15
            [pennTreebankTag] => ,
            [depth] => 12
            [word] => ,
            [index] => 10
            [originalText] => ,
            [lemma] => ,
            [characterOffsetBegin] => 53
            [characterOffsetEnd] => 54
            [pos] => ,
            [ner] => O
            [before] => 
            [after] =>  
        )

    [20] => Array
        (
            [parent] => 10
            [pennTreebankTag] => NP
            [depth] => 12
        )

    [21] => Array
        (
            [parent] => 20
            [pennTreebankTag] => DT
            [depth] => 14
            [word] => an
            [index] => 11
            [originalText] => an
            [lemma] => a
            [characterOffsetBegin] => 55
            [characterOffsetEnd] => 57
            [pos] => DT
            [ner] => O
            [before] =>  
            [after] =>  
        )

    [22] => Array
        (
            [parent] => 20
            [pennTreebankTag] => NN
            [depth] => 14
            [word] => Engineer
            [index] => 12
            [originalText] => Engineer
            [lemma] => engineer
            [characterOffsetBegin] => 58
            [characterOffsetEnd] => 66
            [pos] => NN
            [ner] => TITLE
            [before] =>  
            [after] => 
        )

    [23] => Array
        (
            [parent] => 3
            [pennTreebankTag] => .
            [depth] => 4
            [word] => .
            [index] => 13
            [originalText] => .
            [lemma] => .
            [characterOffsetBegin] => 66
            [characterOffsetEnd] => 67
            [pos] => .
            [ner] => O
            [before] => 
            [after] => 
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

