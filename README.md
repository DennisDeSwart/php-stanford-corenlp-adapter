
# PHP Stanford CoreNLP adapter

PHP adapter for use with Stanford CoreNLP


## Features
- Connect to Stanford University CoreNLP API online
- Connect to Stanford CoreNLP 3.6.0 server
- The package gets the following annotator data: tokenize,ssplit,parse,regexner,pos,depparse,lemma,ner,natlog,openie,mention
- The package creates Part-Of-Speech Trees with depth, parent- and child ID


## OpenIE (adapter version 4.0.0+, only on the Java version)

OpenIE creates "subject-relation-object" tuples. This is similar (but not the same) as the "Subject-Verb-Object" concept of the English language.

Notes:
- OpenIE is only available on the Java offline version, not with the "online" mode. See the installation walkthrough below
- OpenIE data is not always available. Sometimes the result array might show empty, this is not an error.

```
http://nlp.stanford.edu/software/openie.html
https://en.wikipedia.org/wiki/Subject%E2%80%93verb%E2%80%93object
```

## Requirements
- PHP 5.3 or higher: it also works on PHP 7
- Windows or Linux
- Connection to Java server requires cURL.
- Note: Connection to Stanford CoreNLP API online does NOT require cURL.

```
  https://en.wikipedia.org/wiki/CURL
```


## Installation using Composer 

You can install the adapter by putting the following line into your composer.json and running a composer update

```
    {
        "require": {
            "dennis-de-swart/php-stanford-corenlp-adapter": "*"
        }
    }
```



# Using the Stanford CoreNLP online API service



The adapter by default uses Stanford's online API service. This should work right after the composer update.
Note that the online API is a public service. If you want to analyze large volumes of text or sensitive data,
please install the Java server version.



# Installation / Walkthrough for Java server version





## Step 1: install Java

```
https://java.com/en/download/help/index_installing.xml?os=All+Platforms&j=8&n=20
```

## Step 2: installing the Stanford CoreNLP 3.6.0 server 
```
http://stanfordnlp.github.io/CoreNLP/index.html#download
```



## Step 3: Port for server
Default port for the Java server is port 9000. If port 9000 is not available you can change the port in the "bootstrap.php" file. Example:

```
define('CURLURL' , 'http://localhost:9000/');

```


## Step 4: Start the CoreNLP serve from the command line. 

Go to the download directory, then enter the following command:

```
java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000
```
You can change the port to 9001 if port 9000 is busy (see step 3)



## Step 5: Test if the server has started by surfing to it's URL
```
http://localhost:9000/
```
When you surf to this URL, you should see the CoreNLP GUI. If you have problems with installation you can check the manual:
```
http://stanfordnlp.github.io/CoreNLP/corenlp-server.html
```

## Step 6: Set ONLINE_API to FALSE

In "bootstrap.php" set define('ONLINE_API' , FALSE). This tells the Adapter to use the Java version




# Usage examples



## Instantiate the adapter:
```
$coreNLP = new CorenlpAdapter();
```


## To process a text, call the "getOutput" method:
```
 $text = 'The Golden Gate Bridge was designed by Joseph Strauss.'; 
 $coreNLP->getOutput($text);
```

Note that the first time that you process a text, the server takes about 20 to 30 seconds extra to load definitions. All other calls to the server after that will be much faster. Small texts are usually processed within seconds.



## The results

If successful the following properties will be available:
```
 $coreNLP->serverMemory;      //contains all of the server output
 $coreNLP->trees;             //contains processed flat trees. Each part of the tree is assigned an ID key
 
 $coreNLP->getWordValues($coreNLP->trees[1])  // get just the words from a tree
 ```




********************************
### Diagram A: Tree With Tokens
********************************
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
            [parent] => 1
            [pennTreebankTag] => S
            [depth] => 2
        )

    [3] => Array
        (
            [parent] => 2
            [pennTreebankTag] => NP
            [depth] => 4
        )

    [4] => Array
        (
            [parent] => 3
            [pennTreebankTag] => PRP
            [depth] => 6
            [word] => I
            [index] => 1
            [originalText] => I
            [lemma] => I
            [characterOffsetBegin] => 0
            [characterOffsetEnd] => 1
            [pos] => PRP
            [ner] => O
            [before] => 
            [after] =>  
            [openIE] => Array
                (
                    [0] => subject
                    [1] => subject
                    [2] => subject
                )

        )

    [5] => Array
        (
            [parent] => 2
            [pennTreebankTag] => VP
            [depth] => 4
        )

    [6] => Array
        (
            [parent] => 5
            [pennTreebankTag] => MD
            [depth] => 6
            [word] => will
            [index] => 2
            [originalText] => will
            [lemma] => will
            [characterOffsetBegin] => 2
            [characterOffsetEnd] => 6
            [pos] => MD
            [ner] => O
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [0] => subject
                    [1] => subject
                    [2] => relation
                )

        )

    [7] => Array
        (
            [parent] => 5
            [pennTreebankTag] => VP
            [depth] => 6
        )

    [8] => Array
        (
            [parent] => 7
            [pennTreebankTag] => VB
            [depth] => 8
            [word] => meet
            [index] => 3
            [originalText] => meet
            [lemma] => meet
            [characterOffsetBegin] => 7
            [characterOffsetEnd] => 11
            [pos] => VB
            [ner] => O
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [0] => subject
                    [1] => subject
                    [2] => relation
                )

        )

    [9] => Array
        (
            [parent] => 7
            [pennTreebankTag] => NP
            [depth] => 8
        )

    [10] => Array
        (
            [parent] => 9
            [pennTreebankTag] => NP
            [depth] => 10
        )

    [11] => Array
        (
            [parent] => 10
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Mary
            [index] => 4
            [originalText] => Mary
            [lemma] => Mary
            [characterOffsetBegin] => 12
            [characterOffsetEnd] => 16
            [pos] => NNP
            [ner] => PERSON
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [1] => subject
                    [2] => object
                    [3] => subject
                    [0] => subject
                )

        )

    [12] => Array
        (
            [parent] => 9
            [pennTreebankTag] => PP
            [depth] => 10
        )

    [13] => Array
        (
            [parent] => 12
            [pennTreebankTag] => IN
            [depth] => 12
            [word] => in
            [index] => 5
            [originalText] => in
            [lemma] => in
            [characterOffsetBegin] => 17
            [characterOffsetEnd] => 19
            [pos] => IN
            [ner] => O
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [1] => relation
                    [3] => relation
                    [0] => relation
                )

        )

    [14] => Array
        (
            [parent] => 12
            [pennTreebankTag] => NP
            [depth] => 12
        )

    [15] => Array
        (
            [parent] => 14
            [pennTreebankTag] => NNP
            [depth] => 14
            [word] => New
            [index] => 6
            [originalText] => New
            [lemma] => New
            [characterOffsetBegin] => 20
            [characterOffsetEnd] => 23
            [pos] => NNP
            [ner] => LOCATION
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [1] => relation
                    [3] => object
                    [0] => object
                )

        )

    [16] => Array
        (
            [parent] => 14
            [pennTreebankTag] => NNP
            [depth] => 14
            [word] => York
            [index] => 7
            [originalText] => York
            [lemma] => York
            [characterOffsetBegin] => 24
            [characterOffsetEnd] => 28
            [pos] => NNP
            [ner] => LOCATION
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [1] => object
                    [3] => object
                )

        )

    [17] => Array
        (
            [parent] => 7
            [pennTreebankTag] => PP
            [depth] => 8
        )

    [18] => Array
        (
            [parent] => 17
            [pennTreebankTag] => IN
            [depth] => 10
            [word] => at
            [index] => 8
            [originalText] => at
            [lemma] => at
            [characterOffsetBegin] => 29
            [characterOffsetEnd] => 31
            [pos] => IN
            [ner] => O
            [before] =>  
            [after] =>  
            [openIE] => Array
                (
                    [1] => object
                )

        )

    [19] => Array
        (
            [parent] => 17
            [pennTreebankTag] => NP
            [depth] => 10
        )

    [20] => Array
        (
            [parent] => 19
            [pennTreebankTag] => CD
            [depth] => 12
            [word] => 10pm
            [index] => 9
            [originalText] => 10pm
            [lemma] => 10pm
            [characterOffsetBegin] => 32
            [characterOffsetEnd] => 36
            [pos] => CD
            [ner] => TIME
            [normalizedNER] => T22:00
            [before] =>  
            [after] => 
            [timex] => Array
                (
                    [tid] => t1
                    [type] => TIME
                    [value] => T22:00
                )

            [openIE] => Array
                (
                    [0] => object
                    [1] => object
                )

        )

)

 ```
 
***********************************************************************
### Diagram B: The ServerMemory contains all the server data
***********************************************************************
```
Array
(
    [0] => Array
        (
            [sentences] => Array
                (
                    [0] => Array
                        (
                            [index] => 0
                            [parse] => (ROOT
  (S
    (NP (PRP I))
    (VP (MD will)
      (VP (VB meet)
        (NP
          (NP (NNP Mary))
          (PP (IN in)
            (NP (NNP New) (NNP York))))
        (PP (IN at)
          (NP (CD 10pm)))))))
                            [basic-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 3
                                            [dependentGloss] => meet
                                        )

                                    [1] => Array
                                        (
                                            [dep] => nsubj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 1
                                            [dependentGloss] => I
                                        )

                                    [2] => Array
                                        (
                                            [dep] => aux
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 2
                                            [dependentGloss] => will
                                        )

                                    [3] => Array
                                        (
                                            [dep] => dobj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 4
                                            [dependentGloss] => Mary
                                        )

                                    [4] => Array
                                        (
                                            [dep] => case
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 5
                                            [dependentGloss] => in
                                        )

                                    [5] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 6
                                            [dependentGloss] => New
                                        )

                                    [6] => Array
                                        (
                                            [dep] => nmod
                                            [governor] => 4
                                            [governorGloss] => Mary
                                            [dependent] => 7
                                            [dependentGloss] => York
                                        )

                                    [7] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => 10pm
                                            [dependent] => 8
                                            [dependentGloss] => at
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 9
                                            [dependentGloss] => 10pm
                                        )

                                )

                            [collapsed-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 3
                                            [dependentGloss] => meet
                                        )

                                    [1] => Array
                                        (
                                            [dep] => nsubj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 1
                                            [dependentGloss] => I
                                        )

                                    [2] => Array
                                        (
                                            [dep] => aux
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 2
                                            [dependentGloss] => will
                                        )

                                    [3] => Array
                                        (
                                            [dep] => dobj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 4
                                            [dependentGloss] => Mary
                                        )

                                    [4] => Array
                                        (
                                            [dep] => case
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 5
                                            [dependentGloss] => in
                                        )

                                    [5] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 6
                                            [dependentGloss] => New
                                        )

                                    [6] => Array
                                        (
                                            [dep] => nmod:in
                                            [governor] => 4
                                            [governorGloss] => Mary
                                            [dependent] => 7
                                            [dependentGloss] => York
                                        )

                                    [7] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => 10pm
                                            [dependent] => 8
                                            [dependentGloss] => at
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod:at
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 9
                                            [dependentGloss] => 10pm
                                        )

                                )

                            [collapsed-ccprocessed-dependencies] => Array
                                (
                                    [0] => Array
                                        (
                                            [dep] => ROOT
                                            [governor] => 0
                                            [governorGloss] => ROOT
                                            [dependent] => 3
                                            [dependentGloss] => meet
                                        )

                                    [1] => Array
                                        (
                                            [dep] => nsubj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 1
                                            [dependentGloss] => I
                                        )

                                    [2] => Array
                                        (
                                            [dep] => aux
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 2
                                            [dependentGloss] => will
                                        )

                                    [3] => Array
                                        (
                                            [dep] => dobj
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 4
                                            [dependentGloss] => Mary
                                        )

                                    [4] => Array
                                        (
                                            [dep] => case
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 5
                                            [dependentGloss] => in
                                        )

                                    [5] => Array
                                        (
                                            [dep] => compound
                                            [governor] => 7
                                            [governorGloss] => York
                                            [dependent] => 6
                                            [dependentGloss] => New
                                        )

                                    [6] => Array
                                        (
                                            [dep] => nmod:in
                                            [governor] => 4
                                            [governorGloss] => Mary
                                            [dependent] => 7
                                            [dependentGloss] => York
                                        )

                                    [7] => Array
                                        (
                                            [dep] => case
                                            [governor] => 9
                                            [governorGloss] => 10pm
                                            [dependent] => 8
                                            [dependentGloss] => at
                                        )

                                    [8] => Array
                                        (
                                            [dep] => nmod:at
                                            [governor] => 3
                                            [governorGloss] => meet
                                            [dependent] => 9
                                            [dependentGloss] => 10pm
                                        )

                                )

                            [openie] => Array
                                (
                                    [0] => Array
                                        (
                                            [subject] => I
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 0
                                                    [1] => 1
                                                )

                                            [relation] => will meet Mary at
                                            [relationSpan] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 3
                                                )

                                            [object] => 10pm
                                            [objectSpan] => Array
                                                (
                                                    [0] => 8
                                                    [1] => 9
                                                )

                                        )

                                    [1] => Array
                                        (
                                            [subject] => I
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 0
                                                    [1] => 1
                                                )

                                            [relation] => will meet
                                            [relationSpan] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 3
                                                )

                                            [object] => Mary in New York
                                            [objectSpan] => Array
                                                (
                                                    [0] => 3
                                                    [1] => 7
                                                )

                                        )

                                    [2] => Array
                                        (
                                            [subject] => I
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 0
                                                    [1] => 1
                                                )

                                            [relation] => will meet
                                            [relationSpan] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 3
                                                )

                                            [object] => Mary
                                            [objectSpan] => Array
                                                (
                                                    [0] => 3
                                                    [1] => 4
                                                )

                                        )

                                    [3] => Array
                                        (
                                            [subject] => Mary
                                            [subjectSpan] => Array
                                                (
                                                    [0] => 3
                                                    [1] => 4
                                                )

                                            [relation] => is in
                                            [relationSpan] => Array
                                                (
                                                    [0] => 4
                                                    [1] => 5
                                                )

                                            [object] => New York
                                            [objectSpan] => Array
                                                (
                                                    [0] => 5
                                                    [1] => 7
                                                )

                                        )

                                )

                            [tokens] => Array
                                (
                                    [0] => Array
                                        (
                                            [index] => 1
                                            [word] => I
                                            [originalText] => I
                                            [lemma] => I
                                            [characterOffsetBegin] => 0
                                            [characterOffsetEnd] => 1
                                            [pos] => PRP
                                            [ner] => O
                                            [before] => 
                                            [after] =>  
                                        )

                                    [1] => Array
                                        (
                                            [index] => 2
                                            [word] => will
                                            [originalText] => will
                                            [lemma] => will
                                            [characterOffsetBegin] => 2
                                            [characterOffsetEnd] => 6
                                            [pos] => MD
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [2] => Array
                                        (
                                            [index] => 3
                                            [word] => meet
                                            [originalText] => meet
                                            [lemma] => meet
                                            [characterOffsetBegin] => 7
                                            [characterOffsetEnd] => 11
                                            [pos] => VB
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [3] => Array
                                        (
                                            [index] => 4
                                            [word] => Mary
                                            [originalText] => Mary
                                            [lemma] => Mary
                                            [characterOffsetBegin] => 12
                                            [characterOffsetEnd] => 16
                                            [pos] => NNP
                                            [ner] => PERSON
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [4] => Array
                                        (
                                            [index] => 5
                                            [word] => in
                                            [originalText] => in
                                            [lemma] => in
                                            [characterOffsetBegin] => 17
                                            [characterOffsetEnd] => 19
                                            [pos] => IN
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [5] => Array
                                        (
                                            [index] => 6
                                            [word] => New
                                            [originalText] => New
                                            [lemma] => New
                                            [characterOffsetBegin] => 20
                                            [characterOffsetEnd] => 23
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [6] => Array
                                        (
                                            [index] => 7
                                            [word] => York
                                            [originalText] => York
                                            [lemma] => York
                                            [characterOffsetBegin] => 24
                                            [characterOffsetEnd] => 28
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [7] => Array
                                        (
                                            [index] => 8
                                            [word] => at
                                            [originalText] => at
                                            [lemma] => at
                                            [characterOffsetBegin] => 29
                                            [characterOffsetEnd] => 31
                                            [pos] => IN
                                            [ner] => O
                                            [before] =>  
                                            [after] =>  
                                        )

                                    [8] => Array
                                        (
                                            [index] => 9
                                            [word] => 10pm
                                            [originalText] => 10pm
                                            [lemma] => 10pm
                                            [characterOffsetBegin] => 32
                                            [characterOffsetEnd] => 36
                                            [pos] => CD
                                            [ner] => TIME
                                            [normalizedNER] => T22:00
                                            [before] =>  
                                            [after] => 
                                            [timex] => Array
                                                (
                                                    [tid] => t1
                                                    [type] => TIME
                                                    [value] => T22:00
                                                )

                                        )

                                )

                        )

                )

        )

 ```

## Any questions?

Please let me know. 


## Credits

Some functions are forked from this "Stanford parser" package:
```
 https://github.com/agentile/PHP-Stanford-NLP
```

