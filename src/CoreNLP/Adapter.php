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
	
	public $serverRawOutput	= ''; // container for serveroutput
	public $serverOutput	= ''; // container for decoded data
	public $serverMemory	= ''; // keeps all the output
	
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
	
		// all done
		return;
	}
	
/**
 * 
 * GET THE SERVER OUTPUT
 * 
 */

	// keeps parsed trees
	public $trees 		= array();
	
	/**
	 * function getOutput
	 * 
	 * - role: all-in-one function to make life easy for the user
	 */
	public function getOutput($text){
		
		// run the text through CoreNLP
		$this->getServerOutput($text);
		
		// cache result
		$this->serverMemory[] = $this->serverOutput;
		
		foreach($this->serverOutput['sentences']	as	$sentence){
			$tree			= $this->getTree($sentence['parse']); // gets one tree
			$this->trees[]	= $tree; // collect all trees
			
		}
				
		// to get the trees just call $coreNLP->trees in the main program
		return;
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
	public function getTree($sentence){
						
		$this->getSentenceTree($sentence);  // creates tree
		$result = $this->mem;
		$this->resetSentenceTree();
		
		return $result;
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
					$this->mem[$this->memId]['pennTreebankTag'] = $iterator->current();
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
 * 	Helpful functions
 * 
 */
	
	// import token data into the flat tree	
	public function tokensToTree($tokens, $tree){
		
		// step 1: get tree key ID's for each of the words
		$treeWordKeys = $this->getWordKeys($tree);
		
		// step 2: change the keys of the token array to tree IDs
		$tokens = array_combine(array_values($treeWordKeys), $tokens);
		
		//print_r($tokens);
		//print_r($tree);
		
		
		// step 3: import the token array into the tree
		foreach($tree as $treeKey => $part){
			if(array_key_exists($treeKey, $tokens)){
				$tokenItems = $tokens[$treeKey];
				print_r($tokenItems);
				die;
				
					foreach($tokenItems as $key => $item){
						
						if($key != 'pos' && $key != 'originalText'){
							$tree[$treeKey][$key] = $item;
						}
					}	
			}
		}
		
		return $tree;
	}
	
	
	// Get an array that contains the keys to words within the tree
	public function getWordKeys(array $tree){
	
		$result = array();
	
		foreach ($tree as $wordId => $node){
			if(array_key_exists('word', $node)){
				$result[] = $wordId;
			}
		}
		return $result;
	}
	
	// Get an array with the tree parts that contain words
	public function getWordIDs(array $tree){
		
		$result = array();
		
		foreach ($tree as $wordId => $node){
			if(array_key_exists('word', $node)){
				$result[$wordId] = $node;
			}
		}
		return $result;
	}
}
