# php-stanford-corenlp-adapter
PHP adapter for Stanford CoreNLP tools<br /><br />

<p>
<b>Features:</b><br />
- The following annotators are used: Tokenize, Part-Of-Speech tagging, Lemma, NER, regexNER, parse<br />
- It creates Part-Of-SpeechTrees with depth, ID's and parentID's.<br />
- It creates a sorted array for tokens, lemma, NER, regexNER<br />
- It combines the token array with the Tree IDs for further analysis<br />
</p>

<p>
<b>TODO list features:</b>
- Currently working on providing other features, mainly dependency parsing, relation and quote.<br />
</p>

<p>
<b>Installation/ requirements:</b><br />
1) Stanford CoreNLP 3.6.0<br />
- Download / install CoreNLP 3.6.0: http://stanfordnlp.github.io/CoreNLP/index.html#download<br />
- How to use CoreNLP server: http://stanfordnlp.github.io/CoreNLP/corenlp-server.html <br />
- Go to installation directory and start: "java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000" <br />
- If port 9000 is used by another program, use port 9001 <br /><br />
2) PHP cURL<br /> 
- The current version requires cURL. If you need to install, check here for more info: https://en.wikipedia.org/wiki/CURL<br />
- If you don't want to use cURL, I suggest using either "wget" on Linux systems, or Composer/Guzzle. However, this requires recoding. <br /><br />
3) Change the configuration settings in "index.php" for your situation. <br />
</p>

<p>
<b>How to use:</b><br />
See index.php for example usage<br />
</p>

<p>
<b>Notes:</b><br />
- Tested on Windows and Linux<br />
- Tested on PHP 5.3 and PHP 7<br />
- It is designed to be used with Stanford CoreNLP, where CoreNLP is working as a server.<br />
- I plan to make it compatible with the "Stanford Parser" package in the near future.
</p>

