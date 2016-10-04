# php-stanford-corenlp-adapter
PHP adapter for Stanford CoreNLP tools<br /><br />

<b>What does it do</b><br />
It takes a CoreNLP parse and creates a tree with depths, ID's and parentID's. It is designed to be used for Stanford CoreNLP, where CoreNLP is working as a server<br /><br />

<b>What does it not do</b><br />
It does not do lemma's and dependency parsing<br /><br />

<b>Installation/ requirements</b><br />
1) Stanford CoreNLP 3.6.0<br />
- Download / install CoreNLP 3.6.0: http://stanfordnlp.github.io/CoreNLP/index.html#download<br />
- How to use CoreNLP server: http://stanfordnlp.github.io/CoreNLP/corenlp-server.html <br />
- Go to installation directory and start: "java -mx4g -cp "*" edu.stanford.nlp.pipeline.StanfordCoreNLPServer -port 9000" <br />
- If port 9000 is used by another program, use port 9001 <br />

2) PHP CURL<br />
- Download CURL https://curl.haxx.se/download.html<br /><br />

<b>Usage</b><br />
See index.php for example usage




