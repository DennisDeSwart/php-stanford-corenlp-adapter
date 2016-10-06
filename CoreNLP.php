<?php

/* 
 * Stanford Core NLP Adapter
 */

class CoreNLP {
	
	// used as counters in recursion: need to be cleared for every sentence
	public $countDepth;
	public $countID;
	public $lastParent;
	
	public function __construct($clear_ID = true){ 	// true: clearAll, false: clear only depth
		
		// clear counters for every new CoreNLP Object
		if($clear_ID){
			$this->clearAll();	// clears depth, normal ID and parent ID
		} else {
			$this->clearDepth(); // only clears depth
		}
	}
	
	
/**
 * 
 * 	PARSE TREE FUNCTIONS
 * 
 */	
	
// combines common functions to get a tree
	public function getTree(string $text, $showParse = false){
		
		$this->clearDepth();
		$parse 		= $this->getParse($text);				// gets the Java parse
		$parsedText = $this->processParse($parse);			// slice the Stanford parse
		$result 	= $this->getSentenceTree($parsedText);  // creates tree from the slice
		
		if($showParse){ // used for debugging
			echo '<pre>';
			print_r($parse);
		}
		
		return $result;
	}
	
	public function clearID(){
		$this->countID 		= 0;
	}
	
	public function clearDepth() {
		$this->countDepth 	= 0;
		$this->lastParent 	= 0;
	}
	
	public function clearAll() {
		$this->countDepth 	= 0;
		$this->countID 		= 0;
		$this->lastParent 	= 0;
	}
	
	// this sends the parse command to CoreNLP
	private function sendCommandToCore(string $text){
	
		$command = 'curl --data "'.$text.'" "'.CURLURL.'"?properties={"'.CURLPROPERTIES.'"}';
		exec($command, $output, $return);
		
		if($return > 0){
			if(function_exists('curl_strerror')){
				$response = curl_strerror ( $return );
				echo 'CURL error while sending a command to CoreNLP server: '.$response;
				exit;
			} else {
				$response  = 'CURL error while sending command to CoreNLP server, number = '.$return.'<br />';
				$response .= 'Check the error number online and correct the problem.';
				echo $response;
				exit;
			}
				
		} else {
			$response= $output;
		}
	
		return $response;
	}
	
	// tell CoreNLP to parse a line of text 
	public function getParse(string $text){
	
		$response = false;
		$response = $this->sendCommandToCore($text);
	
		return $response;
	}
	
	// process the output from the command line
	public function processParse(array $parse){
		
		$treebankParse = false;
		
		$treebankParseStart  = array_search('(ROOT', $parse);
		$treebankParseFinish = array_search('', $parse);
		
		$treebankParse = array_slice($parse, $treebankParseStart, $treebankParseFinish-$treebankParseStart);
		$treebankParse = implode(PHP_EOL, $treebankParse);
		
		return $treebankParse;
	}

	// getSentenceTree helper: assigns ID's to tree tags
	private function assignId(&$value, $key){
		
		if($key == 'id'){
			$value = $this->countID++;
		}
	}
	
	// getSentenceTree helper: assigns parentID's to tree tags
	private function assignParentId(&$value){
	
		if(is_array($value)){
			if(array_key_exists('children', $value)){
				foreach($value['children'] as &$child){
					$child['parent'] = $value['id'];
				}
			}
			array_walk($value, array($this, 'assignParentId'));
		}
	}
	
	// getSentenceTree helper: assigns depth to tree tags
	private function assignDepth(&$value){
	
		if(is_array($value)){
			if(array_key_exists('id', $value)){
	
				if($this->lastParent == $value['parent']){
					$depth = $this->countDepth++;
				}
	
				$value['depth'] = $this->countDepth;
				if(array_key_exists('children', $value)){
					$depth = $this->countDepth++;
				} else {
					$depth = $this->countDepth--;
				}
				$this->lastParent = $value['parent'];
	
			}
	
			array_walk($value, array($this, 'assignDepth'));
		}
	}
	
	public function getSentenceTree(string $sentence){
		
		// set up the basic tree
		$this->sentenceTree = array();
		$this->sentenceTree = $this->runSentenceTree($sentence);
		$this->sentenceTree['children']['0']['parent'] = 0;
		$this->sentenceTree['depth'] = 0;
		
		// assign ID
		array_walk_recursive($this->sentenceTree, array($this, "assignId"));
		
		// assign ParentID
		array_walk($this->sentenceTree, array($this, 'assignParentId'));
		
		// assign depth
		array_walk($this->sentenceTree, array($this, 'assignDepth'));
		
		return $this->sentenceTree;
	}
	
	/**
	 * Creates tree for parsed sentence
	 * 
	 * Based on https://github.com/agentile/PHP-Stanford-NLP
	 */
	
	private function runSentenceTree(string $sentence)
	{
		$arr 	= array('pennTag' => null, 'id' => null);
		$stack 	= array();
		$length = strlen($sentence);
	
		$id 	= 0;
		$depth  = 0;
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
				$tag_and_word = trim($node);
				$tag_and_word = explode(' ', $tag_and_word);
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
	 * 	TEXT, LEMMA, NER FUNCTIONS
	 * 
	 */
	
	private function find_key_by_substr(string $subString, array $array)
	{
		foreach ($array as $key => $value) {
	
			if(substr($value, 0, strlen($subString)) === $subString){
	
				return $key;
			}
		}
		return false;
	}
	
	// Returns group of arrays that contains annotation info on each word
	public function getTextAnnotators(string $text){
	
		$parse 	= $this->getParse($text);		// gets the Java parse
		$result = $this->runTextAnnotators($parse);	// 
	
		return $result;
	}
	
	private function runTextAnnotators(array $parse){
	
		$textAnnotatorsStart  		 = $this->find_key_by_substr('[Text', $parse);					// it should usually be 2 (= the third row)
		$textAnnotatorsFinishReverse = $this->find_key_by_substr('[Text', array_reverse($parse));	// to find the last key, reverse the array first then search	
		$textAnnotatorsFinish 		 = count($parse)-$textAnnotatorsFinishReverse -$textAnnotatorsStart;
	
		$textAnnotators = array_slice($parse, $textAnnotatorsStart, $textAnnotatorsFinish);
		
		$result = $this->processTextAnnotators($textAnnotators);
		return $result;
	}
	
	private function processTextAnnotators(array $textAnnotators){
	
		foreach ($textAnnotators as $key => $value){
			// remove first and last character
			$value = substr($value, 1, -1);
			$valueArray = explode(' ', $value);
			
			foreach ($valueArray as $value){
				// remove first and last character
				$valuePartArray	 = explode('=', $value);
				$result[$key][$valuePartArray[0]] = $valuePartArray[1];
			}
		}
		
		return $result;
	}
	
	public function getWordIDs(array $tree){
		
		$this->wordToTreeID = array();
		array_walk($tree, array($this, "runWordIDs"));
		
		return $this->wordToTreeID;
	}

	private function runWordIDs($value, $key){
		
		if(is_array($value)){
			if(array_key_exists('word', $value)){
				$this->wordToTreeID[$value['id']] = $value['word'];
			}
			array_walk($value, array($this, "runWordIDs"));
				
		}
	}
	
	public function combineWordIDsAnnotators(array $tree, array $annotators){
	
		$wordIDs = array_keys($this->getWordIDs($tree)); // need the keys
		$result  = array_combine($wordIDs, array_values($annotators));
		
		return $result;
	}
}
