<?php

/* 
 * Stanford Core NLP Adapter
 */

class CorenlpAdapter {
	
/**
 * 
 * COMMAND LINE FUNCTIONS
 * 
 */
    
    public $serverRawOutput = ''; // container for serveroutput
    public $serverOutput    = ''; // container for decoded data
    public $serverMemory    = ''; // keeps all the output

    /**
     * function getOnlineOutput:
     * - sends request to online CoreNLP API
     * - returns JSON reply
     */

     
    public function getServerOutputOnline(string $text){
    
        $doc = new DomDocument();
        $doc->loadHTMLfile(ONLINE_URL.urlencode($text));
        $pre = $doc->getElementsByTagName('pre')->item(0);
        $content = $pre->nodeValue;
        $string = htmlentities($content, null, 'utf-8');
        $content = str_replace("&nbsp;", "", $string);
        $content = html_entity_decode($content);  
        $this->serverRawOutput = $content;
        
        // get object with data
        $this->serverOutput	= json_decode($this->serverRawOutput, true);	// note: decodes into an array, not an object
        return;
    }

    /**
     * function getServerOutput:
     * - sends the server command
     * - returns server output
     * 
     * @param string $text
     * @return type
     */
    public function getServerOutput(string $text){

        // create a shell command
        $command = 'curl --data "'.$text.'" "'.CURLURL.'"?properties={"'.CURLPROPERTIES.'"}';

        try {
                // do the shell command
                $this->serverRawOutput = shell_exec($command);

            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // get object with data
        $this->serverOutput	= json_decode($this->serverRawOutput, true);	// note: decodes into an array, not an object
        
        return;
    }
	
/**
 * 
 * SERVER OUTPUT FUNCTIONS
 * 
 */

    // keeps parsed trees
    public $trees   = array();

    /**
     * function getOutput
     * 
     * - role: all-in-one function to make life easy for the user
     */
    public function getOutput(string $text){

        if(ONLINE_API){
            // run the text through the public API
            $this->getServerOutputOnline($text);
        } else{
            // run the text through Java CoreNLP
            $this->getServerOutput($text);
        }
        
        // cache result
        $this->serverMemory[] = $this->serverOutput;

        if(empty($this->serverOutput)){
            echo '** ERROR: No output from the CoreNLP Server **<br />
                - Check if the CoreNLP server is running. Start the CoreNLP server if necessary<br />
                - Check if the port you are using (probably port 9000) is not blocked by another program<br />';
            die;
        }

        /**
         * create trees
         */
        $sentences = $this->serverOutput['sentences'];
        foreach($this->serverOutput['sentences'] as $sentence){
            $tree           = $this->getTreeWithTokens($sentence); // gets one tree
            $this->trees[]  = $tree; // collect all trees
        }
        
        /**
         * add OpenIE data
         */
        $this->addOpenIE();
        
        // to get the trees just call $coreNLP->trees in the main program
        return;
    }
	
    
/**
 * 
 * MAIN PARSING FUNCTIONS
 * 
 */
     /**
      * Gets tree from parse
      * 
      * @param string $parse
      * @return array
      */
    public function getTree(string $parse){

        $this->getSentenceTree($parse);  // creates tree from parse, then saves tree in "mem"
        $result = $this->mem;            // get tree from "mem"    
        $this->resetSentenceTree();      // clear "mem"

        return (array) $result;
    }
    
    /**
     * Gets tree that combines depth/ parent information with the tokens
     * 
     * @param array $sentence
     * @return array
     */
    public function getTreeWithTokens(array $sentence){
        
        $parse  = $sentence['parse'];
        $tokens = $sentence['tokens'];
        
        // get simple tree
        $tree = $this->getTree($parse);
      
        // step 1: get tree key ID's for each of the words
        $treeWordKeys = $this->getWordKeys($tree);

        // step 2: change the keys of the token array to tree IDs
        $combinedTokens = array_combine(array_values($treeWordKeys), $tokens);

        // step 3: import the token array into the tree
        foreach($tree as $treeKey => $value){
            if(array_key_exists($treeKey, $combinedTokens)){
                $tokenItems = $combinedTokens[$treeKey];
             
                foreach($tokenItems as $tokenKey => $token){           
                    $tree[$treeKey][$tokenKey] = $token;                  
                }	
            }
        }
        return $tree;
    }
    
    /**
     * helpers for SentenceTree
     */
    private $mem;
    private $memId;
    private $memparent;
    private $iteratorDepth;
    private $memDepth;
    private $parentId;
    private $sentenceTree = array();

    /**
     * resets SentenceTree
     */
    private function resetSentenceTree(){
        $this->mem          = array();
        $this->memId        = 0;
        $this->memparent    = array();
        $this->iteratorDepth= 0;
        $this->memDepth     = -1;
        $this->parentId     = 0;
        $this->sentenceTree = array();
    }
   
    /**
     * Takes one $sentence and creates a flat tree with:
     * - parentId
     * - penn Treebank Tag
     * - depth
     * - word value
     * 
     * @param string $sentence
     */
    public function getSentenceTree(string $sentence){
	
        // parse the tree
        $this->sentenceTree = $this->runSentenceTree($sentence);

        $iterator = new RecursiveIteratorIterator(
        new RecursiveArrayIterator($this->sentenceTree));

        for($iterator->next(); $iterator->valid(); $iterator->next())
        {
            if(!is_array($iterator->current())){

            $this->iteratorDepth = $iterator->getDepth();

                if($this->iteratorDepth > $this->memDepth){

                    $this->depthShiftUp();

                } else if($this->iteratorDepth < $this->memDepth){

                    $this->depthShiftDown();

                } else {

                    if($iterator->key() == 'pennTag'){
                        $this->memId++;
                        $this->mem[$this->memId]['parent'] = $this->parentId;
                    }
                }

                if($iterator->key() == 'pennTag'){
                    $this->mem[$this->memId]['pennTreebankTag'] = $iterator->current();
                    $this->mem[$this->memId]['depth'] = $this->iteratorDepth;
                }

                if($iterator->key() == 'word'){
                    $this->mem[$this->memId]['word'] = $iterator->current();
                }
            } 
        }
    }
    
    /**
     * helper for SentenceTree iteration
     */
    private function depthShiftUp(){
        
        // remember the parent
        $this->parentId = $this->memId;

        // set new id for iteration
        $this->memId++;

        // set parent
        $this->mem[$this->memId]['parent'] = $this->parentId;

        // remember parent
        $this->memparent[$this->memDepth] = $this->parentId;

        // set new depth
        $this->memDepth = $this->iteratorDepth;
    }
    
     /**
     * helper for SentenceTree iteration
     */
    private function depthShiftDown(){
        
        // set new id for iteration
        $this->memId++;
        
        // set new depth
        $this->memDepth = $this->iteratorDepth;
        
        // set new parent
        $this->parentId = ($this->memDepth)-2;
        
        // write parent to tree
        $this->mem[$this->memId]['parent'] = $this->memparent[$this->parentId] ;
    }
		
    /**
     * Creates tree for parsed sentence
     * Based on https://github.com/agentile/PHP-Stanford-NLP
     * 
     * @param string $sentence
     * @return type
     */
    private function runSentenceTree(string $sentence)
    {
        $arr 	= array('pennTag' => null);
        $length = strlen($sentence);
        $node 	= '';
        $bracket= 1;

        for ($i = 1; $i < $length; $i++) {

            if ($sentence[$i] == '(') {
                $bracket += 1;
                $match_i = $this->getMatchingBracket($sentence, $i);
                $arr['children'][] = $this->runSentenceTree(substr($sentence, $i, ($match_i - $i) + 1));
                $i = $match_i - 1;  
            } else if ($sentence[$i] == ')') {                
                $bracket -= 1;
                $tag_and_word = explode(' ', trim($node));
                $arr['pennTag'] 	= $tag_and_word[0];
                
                if (array_key_exists('1', $tag_and_word)){
                    $arr['word']	= $tag_and_word[1];
                }
                
            } else {
                    $node .= $sentence[$i];
            }
            
            if ($bracket == 0) {
                    return $arr;
            }
        }

        return $arr;
    }
	
  /**
   * Find the position of a matching closing bracket for a string opening bracket
   * 
   * @param string $string
   * @param int $start_pos
   * @return type
   */
    private function getMatchingBracket(string $string, int $start_pos)
    {
        $length = strlen($string);
        $bracket = 1;
        foreach (range($start_pos + 1, $length) as $i) {
            if ($string[$i] == '(') {
                    $bracket += 1;
            } else if ($string[$i] == ')') {
                    $bracket -= 1;
            }
            if ($bracket == 0) {
                    return $i;
            }
        }
    }
	
/**
 * 
 * OTHER PARSING FUNCTIONS
 * 
 */
    
    // Get an array that contains the keys to words within one tree
    public function getWordKeys(array $tree){

        $result = array();

        foreach ($tree as $wordId => $node){
                if(array_key_exists('word', $node)){
                        $result[] = $wordId;
                }
        }
        return $result;
    }

    // Get an array with the tree leaves that contain words
    public function getWordValues(array $tree){

        $result = array();

        foreach ($tree as $wordId => $node){
                if(array_key_exists('word', $node)){
                        $result[$wordId] = $node;
                }
        }
        return $result;
    }

/**
 * OpenIE functions
 */    
    public function addOpenIE(){
        
        foreach($this->serverOutput['sentences'] as $key => $sentence){

            if(array_key_exists('openie', $sentence)){

                $openIEs = $sentence['openie'];

                foreach($openIEs as $keyOpenIE => $openIE){

                    if(!empty($openIE)){

                        foreach($this->trees[$key] as &$node){

                            if(array_key_exists('index', $node)){

                                if( $node['index']-1 >= $openIE['subjectSpan'][0]  && $node['index']-1 < $openIE['subjectSpan'][1] ){
                                    $node['openIE'][$keyOpenIE] = 'subject';
                                }

                                if( ($node['index']-1 >= $openIE['relationSpan'][0])  && $node['index']-1 < $openIE['relationSpan'][1] ){
                                    $node['openIE'][$keyOpenIE] = 'relation';
                                }

                                if( ($node['index']-1 >= $openIE['objectSpan'][0]) && $node['index']-1 < $openIE['objectSpan'][1] ){
                                    $node['openIE'][$keyOpenIE] = 'object';
                                }
                            }
                        }
                    }    
                }            
            }
        }
    }
    
    
}
