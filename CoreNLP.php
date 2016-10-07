<?php

/* 
 * Stanford Core NLP Adapter
 */

class CoreNLP {
	
	
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
 * SETUP AND COMMAND LINE FUNCTIONS
 * 
 */
	
	public $serverOutput = array(); // container for serveroutput
	
	/**
	 * function getServerOutput
	 * 
	 * - sends the server command
	 * - returns server output
	 */
	public function getServerOutput(string $text){
	
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
			$this->serverOutput = $output;
		}
	
		return;
	}
	
	
/**
 * 
 * FORMATTING SERVER OUTPUT
 * 
 */

	// keeps parsed trees
	public $trees 		= array();
	
	// keeps parsed annotators
	public $annotators	= array();
	
	// keeps parsed annotators with tree ID's
	public $annotatorsWithTrees = array();
	
	/**
	 * function getOutput
	 * 
	 * - role: all-in-one function to make life easy for the user
	 */
	public function getOutput($text){
		
		$splitOutput = $this->getSplitOutput($text);
		foreach($splitOutput as $partOfOutput){
		
			$tree 				= $this->getTree($partOfOutput); // true: prints the server raw output
			$annotator 			= $this->getAnnotators($partOfOutput);
			$annotatorsWithTree = $this->add_TreeIDs_To_Annotators($tree, $annotator);
		
			$this->trees[]			= $tree;
			$this->annotators[]		= $annotator;
		
			$this->annotatorsWithTrees[] 	= $annotatorsWithTree;
		}
	}
	
	/**
	 *  function GetOutputKeys
	 *  
	 *  - role: OutputSplitter helper
	 * 	- Takes the server output and creates calculates start position of each sentence output.
	 */
	private function getOutputKeys(){
		
		$outputKeys 		= array();
		$outputKeysCounter 	= 0;
		
		foreach($this->serverOutput as $key => $value){
			
			if(substr($value, 0, 10) == 'Sentence #'){
			
				// this is the start of a sentence output, unless this is part of of a sentence text itself.
				if(array_key_exists($key-1, $this->serverOutput)){
				
					if(substr($this->serverOutput[$key-1], 0, 10) == 'Sentence #'){
						
						// this is the start of a sentence text and not the start of the sentence output
						continue;
					}
					
					// definitely the start of sentence output
					// - start of output is the current key
					// - finish of sentence output is the previous key: unless this is the start, but we already checked for that
				
					$outputKeysCounter++;
					$outputKeys[$outputKeysCounter	]['start'] 	= $key;
					$outputKeys[$outputKeysCounter-1]['finish'] = $key-1;
				}
			}
		}
		
		// add start
		$outputKeys[0]['start'] = 0;
		
		// add finish
		$outputKeys[$outputKeysCounter]['finish'] = count($this->serverOutput)-1;
		
		// clean up for easier debugging
		ksort($outputKeys);
		
		// return the result
		return $outputKeys;
	}
	
	/**
	 * Function runSplitOutput
	 * 
	 * - role: split output into sentence parts
	 * - Because most other functions are designed to handle sentence parts only
	 */
	
	private function runSplitOutput(){
		
		// define a splitOutput
		$splitOutput = array();
		
		// get outputKeys
		$outputKeys = $this->getOutputKeys($this->serverOutput);
		
		foreach ($outputKeys as $key=>$value){
			
			$splitOutput[] = array_slice($this->serverOutput, $value['start'], $value['finish']-$value['start']);
			
		}
		
		return $splitOutput;
	}
	
	/**
	 * Function getSplitOutput
	 * 
	 * Splits the output to parts for each sentence
	 */
	public function getSplitOutput($text){
	
		$this->getServerOutput($text);
		$splitOutput	= $this->runSplitOutput();
		
		return $splitOutput;
	}
	
/**
 * 
 * 	PARSE TREE FUNCTIONS
 * 
 */	
	
	// used as counters in recursion: need to be cleared for every sentence
	public $countDepth;
	public $countID;
	public $lastParent;
	
	public function clearDepth() {
		$this->countDepth 	= 0;
		$this->lastParent 	= 0;
	}
	
	public function clearID(){
		$this->countID 		= 0;
	}
	
	public function clearAll() {
		$this->countDepth 	= 0;
		$this->countID 		= 0;
		$this->lastParent 	= 0;
	}
	
	/**
	 *  Gets tree for one sentence
	 *  
	 *  input	: array $splitOutput
	 *  output	: array Part-Of-Speech tree
	 */
	public function getTree(array $splitOutput){
		
		$this->clearDepth();								// always clear depth before getting a tree
		$parsedText = $this->parseSplitOutput($splitOutput);	// process the output
		$result 	= $this->getSentenceTree($parsedText);  // creates tree from the slice
		
		return $result;
	}
	
	// process the output from the command line
	public function parseSplitOutput(array $splitOutput){
		
		$treebankParse = false;
		
		$treebankParseStart  = array_search('(ROOT', $splitOutput);
		$treebankParseFinish = array_search('', $splitOutput);
		
		$treebankParse = array_slice($splitOutput, $treebankParseStart, $treebankParseFinish-$treebankParseStart);
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
	public function getAnnotators(array $splitOutput){
	
		$result = $this->runAnnotators($splitOutput);	// 
	
		return $result;
	}
	
	private function runAnnotators(array $parse){
	
		$AnnotatorsStart  		 = $this->find_key_by_substr('[Text', $parse);					// it should usually be 2 (= the third row)
		$AnnotatorsFinishReverse = $this->find_key_by_substr('[Text', array_reverse($parse));	// to find the last key, reverse the array first then search	
		$AnnotatorsFinish 		 = count($parse)-$AnnotatorsFinishReverse -$AnnotatorsStart;
	
		$Annotators = array_slice($parse, $AnnotatorsStart, $AnnotatorsFinish);
		
		$result = $this->processAnnotators($Annotators);
		return $result;
	}
	
	private function processAnnotators(array $Annotators){
	
		foreach ($Annotators as $key => $value){
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
	
	public function add_TreeIDs_To_Annotators(array $tree, array $annotators){
	
		$wordIDs = array_keys($this->getWordIDs($tree)); // need the keys
		$result  = array_combine($wordIDs, array_values($annotators));
		
		return $result;
	}
}
