
# PHP Stanford CoreNLP adapter

PHP adapter for use with Stanford CoreNLP
<br />
## Features
- Connect to Stanford University CoreNLP API online
- Connect to Stanford CoreNLP 3.6.0 server
- The package gets the following annotator data: tokenize,ssplit,parse,regexner,pos,depparse,lemma,ner,natlog,openie,dcoref,mention,coref
- The package creates Part-Of-Speech Trees with depth, parent- and child ID
<br />

## Requirements
- PHP 5.3 or higher: it also works on PHP 7
- Windows or Linux
- Connection to Java server requires cURL.
- Note: Connection to Stanford CoreNLP API online does NOT require cURL.

```
  https://en.wikipedia.org/wiki/CURL
```
<br />

## Installation using Composer 

You can install the adapter by putting the following line into your composer.json and running a composer update

```
    {
        "require": {
            "dennis-de-swart/php-stanford-corenlp-adapter": "*"
        }
    }
```

<br />
# Using the Stanford CoreNLP online API service
<br />

The adapter by default uses Stanford's online API service. This should work right after the composer update.
Note that the online API is a public service. If you want to analyze large volumes of text or sensitive data,
please install the Java server version.

<br />
# Installation / Walkthrough for Java server version
<br />

## Step 1: install Java

```
https://java.com/en/download/help/index_installing.xml?os=All+Platforms&j=8&n=20
```

## Step 2: installing the Stanford CoreNLP 3.6.0 server 
```
http://stanfordnlp.github.io/CoreNLP/index.html#download
```
<br />
## Step 3: Port for server
Default port for the Java server is port 9000. If port 9000 is not available you can change the port in the "bootstrap.php" file. Example:

```
define('CURLURL' , 'http://localhost:9000/');

```


## Step 4: Start the CoreNLP serve from the command line. 

```
java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000
```
You can change the port to 9001 if port 9000 is busy.

<br />
## Step 5: Test if the server has started by surfing to it's URL
```
http://localhost:9000/
```
When you surf to this URL, you should see the CoreNLP GUI. If you have problems with installation you can check the manual:
```
http://stanfordnlp.github.io/CoreNLP/corenlp-server.html
```

<br />
# Usage examples
<br />

## Instantiate the adapter:
```
$coreNLP = new CorenlpAdapter();
```
<br />
## To process a text, call the "getOutput" method:
```
 $text = 'The Golden Gate Bridge was designed by Joseph Strauss.'; 
 $coreNLP->getOutput($text);
```

Note that the first time that you process a text, the server takes about 20 to 30 seconds extra to load definitions. All other calls to the server after that will be much faster. Small texts are usually processed within seconds.
<br /><br />
## The results

If successful the following properties will be available:
```
 $coreNLP->serverMemory;      //contains all of the server output
 $coreNLP->trees;             //contains processed flat trees. Each part of the tree is assigned an ID key
 
 $coreNLP->getWordValues($coreNLP->trees[1])  // get just the words from a tree + 
 ```
## See index.php for more examples
<br /><br /> 

********************************
### Diagram A: Tree With Tokens
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
            [pennTreebankTag] => DT
            [depth] => 6
            [word] => The
            [index] => 1
            [lemma] => the
            [characterOffsetBegin] => 0
            [characterOffsetEnd] => 3
            [pos] => DT
            [ner] => O
            [speaker] => PER0
        )

    [5] => Array
        (
            [parent] => 3
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Golden
            [index] => 2
            [lemma] => Golden
            [characterOffsetBegin] => 4
            [characterOffsetEnd] => 10
            [pos] => NNP
            [ner] => LOCATION
            [speaker] => PER0
        )

    [6] => Array
        (
            [parent] => 3
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Gate
            [index] => 3
            [lemma] => Gate
            [characterOffsetBegin] => 11
            [characterOffsetEnd] => 15
            [pos] => NNP
            [ner] => LOCATION
            [speaker] => PER0
        )

    [7] => Array
        (
            [parent] => 3
            [pennTreebankTag] => NNP
            [depth] => 6
            [word] => Bridge
            [index] => 4
            [lemma] => Bridge
            [characterOffsetBegin] => 16
            [characterOffsetEnd] => 22
            [pos] => NNP
            [ner] => LOCATION
            [speaker] => PER0
        )

    [8] => Array
        (
            [parent] => 2
            [pennTreebankTag] => VP
            [depth] => 4
        )

    [9] => Array
        (
            [parent] => 8
            [pennTreebankTag] => VBD
            [depth] => 6
            [word] => was
            [index] => 5
            [lemma] => be
            [characterOffsetBegin] => 23
            [characterOffsetEnd] => 26
            [pos] => VBD
            [ner] => O
            [speaker] => PER0
        )

    [10] => Array
        (
            [parent] => 8
            [pennTreebankTag] => VP
            [depth] => 6
        )

    [11] => Array
        (
            [parent] => 10
            [pennTreebankTag] => VBN
            [depth] => 8
            [word] => designed
            [index] => 6
            [lemma] => design
            [characterOffsetBegin] => 27
            [characterOffsetEnd] => 35
            [pos] => VBN
            [ner] => O
            [speaker] => PER0
        )

    [12] => Array
        (
            [parent] => 10
            [pennTreebankTag] => PP
            [depth] => 8
        )

    [13] => Array
        (
            [parent] => 12
            [pennTreebankTag] => IN
            [depth] => 10
            [word] => by
            [index] => 7
            [lemma] => by
            [characterOffsetBegin] => 36
            [characterOffsetEnd] => 38
            [pos] => IN
            [ner] => O
            [speaker] => PER0
        )

    [14] => Array
        (
            [parent] => 12
            [pennTreebankTag] => NP
            [depth] => 10
        )

    [15] => Array
        (
            [parent] => 14
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Joseph
            [index] => 8
            [lemma] => Joseph
            [characterOffsetBegin] => 39
            [characterOffsetEnd] => 45
            [pos] => NNP
            [ner] => PERSON
            [speaker] => PER0
        )

    [16] => Array
        (
            [parent] => 14
            [pennTreebankTag] => NNP
            [depth] => 12
            [word] => Strauss
            [index] => 9
            [lemma] => Strauss
            [characterOffsetBegin] => 46
            [characterOffsetEnd] => 53
            [pos] => NNP
            [ner] => PERSON
            [speaker] => PER0
        )

    [17] => Array
        (
            [parent] => 2
            [pennTreebankTag] => .
            [depth] => 4
            [word] => .
            [index] => 10
            [lemma] => .
            [characterOffsetBegin] => 53
            [characterOffsetEnd] => 54
            [pos] => .
            [ner] => O
            [speaker] => PER0
        )

)

 ```
 
***********************************************************************
### Diagram B: The ServerMemory contains all the server data
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
          (NP (NNP Joseph) (NNP Strauss)))))
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

                                )

                            [tokens] => Array
                                (
                                    [0] => Array
                                        (
                                            [index] => 1
                                            [word] => The
                                            [lemma] => the
                                            [characterOffsetBegin] => 0
                                            [characterOffsetEnd] => 3
                                            [pos] => DT
                                            [ner] => O
                                            [speaker] => PER0
                                        )

                                    [1] => Array
                                        (
                                            [index] => 2
                                            [word] => Golden
                                            [lemma] => Golden
                                            [characterOffsetBegin] => 4
                                            [characterOffsetEnd] => 10
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [speaker] => PER0
                                        )

                                    [2] => Array
                                        (
                                            [index] => 3
                                            [word] => Gate
                                            [lemma] => Gate
                                            [characterOffsetBegin] => 11
                                            [characterOffsetEnd] => 15
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [speaker] => PER0
                                        )

                                    [3] => Array
                                        (
                                            [index] => 4
                                            [word] => Bridge
                                            [lemma] => Bridge
                                            [characterOffsetBegin] => 16
                                            [characterOffsetEnd] => 22
                                            [pos] => NNP
                                            [ner] => LOCATION
                                            [speaker] => PER0
                                        )

                                    [4] => Array
                                        (
                                            [index] => 5
                                            [word] => was
                                            [lemma] => be
                                            [characterOffsetBegin] => 23
                                            [characterOffsetEnd] => 26
                                            [pos] => VBD
                                            [ner] => O
                                            [speaker] => PER0
                                        )

                                    [5] => Array
                                        (
                                            [index] => 6
                                            [word] => designed
                                            [lemma] => design
                                            [characterOffsetBegin] => 27
                                            [characterOffsetEnd] => 35
                                            [pos] => VBN
                                            [ner] => O
                                            [speaker] => PER0
                                        )

                                    [6] => Array
                                        (
                                            [index] => 7
                                            [word] => by
                                            [lemma] => by
                                            [characterOffsetBegin] => 36
                                            [characterOffsetEnd] => 38
                                            [pos] => IN
                                            [ner] => O
                                            [speaker] => PER0
                                        )

                                    [7] => Array
                                        (
                                            [index] => 8
                                            [word] => Joseph
                                            [lemma] => Joseph
                                            [characterOffsetBegin] => 39
                                            [characterOffsetEnd] => 45
                                            [pos] => NNP
                                            [ner] => PERSON
                                            [speaker] => PER0
                                        )

                                    [8] => Array
                                        (
                                            [index] => 9
                                            [word] => Strauss
                                            [lemma] => Strauss
                                            [characterOffsetBegin] => 46
                                            [characterOffsetEnd] => 53
                                            [pos] => NNP
                                            [ner] => PERSON
                                            [speaker] => PER0
                                        )

                                    [9] => Array
                                        (
                                            [index] => 10
                                            [word] => .
                                            [lemma] => .
                                            [characterOffsetBegin] => 53
                                            [characterOffsetEnd] => 54
                                            [pos] => .
                                            [ner] => O
                                            [speaker] => PER0
                                        )

                                )

                        )

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

