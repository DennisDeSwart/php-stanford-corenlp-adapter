# php-stanford-corenlp-adapter
PHP adapter for Stanford CoreNLP tools<br /><br />

<b>What does it do</b><br />
It feeds a sentence or text to a CoreNLP server and creates: <br />
- Part-Of-Speech Tree with depth, ID's and parentID's.<br />
- Tokenize, Lemma, NER array. This process also adds tree ID's for matching data
- Depency parsing array.  
<br />

<b>What does it not do</b><br />
CoreNLP has other features like Sentence Splitting that are not covered yet. I will add those as my spare time allows.<br /><br />

<b>Installation/ requirements</b><br />
1) Stanford CoreNLP 3.6.0<br />
- Download / install CoreNLP 3.6.0: http://stanfordnlp.github.io/CoreNLP/index.html#download<br />
- How to use CoreNLP server: http://stanfordnlp.github.io/CoreNLP/corenlp-server.html <br />
- Go to installation directory and start: "java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000" <br />
- If port 9000 is used by another program, use port 9001 <br />

2) PHP cURL<br /> 
- The current version requires cURL. If you need to install, check here for more info: https://en.wikipedia.org/wiki/CURL<br />
- If you don't want to use cURL, I suggest using either "wget" on Linux systems, or Composer/Guzzle. However, this requires recoding. <br />

3) Change the configuration settings in "index.php" for your situation. <br />

<b>Usage</b><br />
See index.php for example usage

<b>Notes</b><br />
- Tested on Windows and Linux<br />
- Tested on PHP 5.3 and PHP 7<br />
- It is designed to be used with Stanford CoreNLP, where CoreNLP is working as a server.<br />
- I plan to make it compatible with the "Stanford Parser" package in the near future.


