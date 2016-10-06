# php-stanford-corenlp-adapter
PHP adapter for Stanford CoreNLP tools<br /><br />

<b>What does it do</b><br />
It takes a Part-Of-Speech(POS) parse from CoreNLP and creates: <br />
- Part-Of-Speech Tree with depths, ID's and parentID's.<br />
- Annotator table with Text, Lemma and NER information<br />
<br />

<b>What does it not do</b><br />
It does not do dependency parsing (yet)<br /><br />

<b>Installation/ requirements</b><br />
1) Stanford CoreNLP 3.6.0<br />
- Download / install CoreNLP 3.6.0: http://stanfordnlp.github.io/CoreNLP/index.html#download<br />
- How to use CoreNLP server: http://stanfordnlp.github.io/CoreNLP/corenlp-server.html <br />
- Go to installation directory and start: "java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000" <br />
- If port 9000 is used by another program, use port 9001 <br />

2) PHP cURL: If you haven't got cURL installed: download here: https://curl.haxx.se/download.html<br />
3) Change the configuration settings in "index.php" for your situation. <br />

<b>Usage</b><br />
See index.php for example usage

<b>Notes</b><br />
- Tested on Windows and Linux<br />
- Tested on PHP 5.3 and PHP 7<br />
- It is designed to be used with Stanford CoreNLP, where CoreNLP is working as a server. However, it could easily be modified to take a parse from the Stanford online parser here: http://nlp.stanford.edu:8080/parser/index.jsp, since the POS parse is of the same format



