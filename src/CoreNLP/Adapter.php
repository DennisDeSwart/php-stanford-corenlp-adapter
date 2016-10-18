<?php

/* 
 * Stanford Core NLP Adapter
 */

class Adapter {
	
/**
 * 
 * SETUP AND COMMAND LINE FUNCTIONS
 * 
 */
	
	/**
	 * function getServerOutput
	 *
	 * - sends the server command
	 * - returns server output
	 */
	
	public $serverOutput = array(); // container for serveroutput
	
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
	
	/**
	 *  Gets tree for one sentence
	 *  
	 *  input	: array $splitOutput
	 *  output	: array Part-Of-Speech tree
	 */
	public function getTree(array $splitOutput){
						
		$parsedText = $this->parseSplitOutput($splitOutput);	// process the output
		$this->getSentenceTree($parsedText);  // creates tree from the slice
		$result = $this->mem;
		$this->resetSentenceTree();
		
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

	
	// helper vars for tree traverse
	public $mem;
	public $memId;
	public $memparent;
	public $memDepth;
	public $parentId;
	
	private function resetSentenceTree(){
		 		$this->mem			= array();
		 		$this->memId 		= 0;
		 		$this->memparent 	= array();
		 		$this->memDepth		= -1;
		 		$this->parentId		= 0;
	}
	
	public function getSentenceTree(string $sentence){
	
		// sets up a normal array tree with tags and words
		$this->sentenceTree = array();
		$this->sentenceTree = $this->runSentenceTree($sentence);

		/**
		 * Iterator creates a flat tree with:
		 * - parentId
		 * - pTag
		 * - depth
		 * - word (only for tree leafs)
		 */
		$iterator = new RecursiveIteratorIterator(
				new RecursiveArrayIterator($this->sentenceTree),
				RecursiveIteratorIterator::SELF_FIRST
				);
	
		for($iterator; $iterator->valid(); $iterator->next())
		{
			
			if(!is_array($iterator->current())){
				
				$newDepth = $iterator->getDepth();
					
				if($newDepth > $this->memDepth){
				
					// remember the parent
					$this->parentId = $this->memId;
					
					// set new id for this child
					$this->memId++;
					
					// set parent
					$this->mem[$this->memId]['parent'] = $this->parentId;
					
					// remember parent
					$this->memparent[$this->memDepth] = $this->parentId;
						
					// set new depth
					$this->memDepth = $newDepth;
				
					
				} else if($newDepth < $this->memDepth){
									
					$this->memId++;
					$this->memDepth = $newDepth;
					$this->parentId = ($this->memDepth)-2;
		
					$this->mem[$this->memId]['parent'] = $this->memparent[$this->parentId] ;
					
				} else {
					
					if($iterator->key() == 'pennTag'){
						$this->memId++;
						$this->mem[$this->memId]['parent'] = $this->parentId;
					}
					
				}
				
				
				if($iterator->key() == 'pennTag'){
					$this->mem[$this->memId]['pTag'] = $iterator->current();
					$this->mem[$this->memId]['depth'] = $newDepth;
				}
					
				if($iterator->key() == 'word'){
					$this->mem[$this->memId]['word'] = $iterator->current();
				}
			} 
		}
	}
		
	/**
	 * Creates tree for parsed sentence
	 * 
	 * Based on https://github.com/agentile/PHP-Stanford-NLP
	 */
	
	private function runSentenceTree(string $sentence)
	{
		$arr 	= array('pennTag' => null);
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
		
		$result = array();
		
		foreach ($tree as $wordId => $node){
			if(array_key_exists('word', $node)){
				$result[$wordId] = $node;
			}
		}
		return $result;
	}

	public function add_TreeIDs_To_Annotators(array $tree, array $annotators){
	
		$wordIDs = array_keys($this->getWordIDs($tree)); // need the keys
		$result  = array_combine($wordIDs, array_values($annotators));
		
		return $result;
	}
}
