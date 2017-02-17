<?php
define('NAMEF', $_SERVER['SERVER_NAME']);
define('DIRF', dirname($_SERVER['PHP_SELF']));
class ParseMaster {
		public $ignoreCase = false;
		public $escapeChar = '';
		
		// constants
		const EXPRESSION = 0;
		const REPLACEMENT = 1;
		const LENGTH = 2;
		
		// used to determine nesting levels
		private $GROUPS = '/\\(/';//g
		private $SUB_REPLACE = '/\\$\\d/';
		private $INDEXED = '/^\\$\\d+$/';
		private $TRIM = '/([\'"])\\1\\.(.*)\\.\\1\\1$/';
		private $ESCAPE = '/\\\./';//g
		private $QUOTE = '/\'/';
		private $DELETED = '/\\x01[^\\x01]*\\x01/';//g
		
		public function add($expression, $replacement = '') {
			// count the number of sub-expressions
			//  - add one because each pattern is itself a sub-expression
			$length = 1 + preg_match_all($this->GROUPS, $this->_internalEscape((string)$expression), $out);
			
			// treat only strings $replacement
			if (is_string($replacement)) {
				// does the pattern deal with sub-expressions?
				if (preg_match($this->SUB_REPLACE, $replacement)) {
					// a simple lookup? (e.g. "$2")
					if (preg_match($this->INDEXED, $replacement)) {
						// store the index (used for fast retrieval of matched strings)
						$replacement = (int)(substr($replacement, 1)) - 1;
					} else { // a complicated lookup (e.g. "Hello $2 $1")
						// build a function to do the lookup
						$quote = preg_match($this->QUOTE, $this->_internalEscape($replacement))
								 ? '"' : "'";
						$replacement = array(
							'fn' => '_backReferences',
							'data' => array(
								'replacement' => $replacement,
								'length' => $length,
								'quote' => $quote
							)
						);
					}
				}
			}
			// pass the modified arguments
			if (!empty($expression)) $this->_add($expression, $replacement, $length);
			else $this->_add('/^$/', $replacement, $length);
		}
		
		public function exec($string) {
			// execute the global replacement
			$this->_escaped = array();
			
			// simulate the _patterns.toSTring of Dean
			$regexp = '/';
			foreach ($this->_patterns as $reg) {
				$regexp .= '(' . substr($reg[self::EXPRESSION], 1, -1) . ')|';
			}
			$regexp = substr($regexp, 0, -1) . '/';
			$regexp .= ($this->ignoreCase) ? 'i' : '';
			
			$string = $this->_escape($string, $this->escapeChar);
			$string = preg_replace_callback(
				$regexp,
				array(
					&$this,
					'_replacement'
				),
				$string
			);
			$string = $this->_unescape($string, $this->escapeChar);
			
			return preg_replace($this->DELETED, '', $string);
		}
			
		public function reset() {
			// clear the patterns collection so that this object may be re-used
			$this->_patterns = array();
		}

		// private
		private $_escaped = array();  // escaped characters
		private $_patterns = array(); // patterns stored by index
		
		// create and add a new pattern to the patterns collection
		private function _add() {
			$arguments = func_get_args();
			$this->_patterns[] = $arguments;
		}
		
		// this is the global replace function (it's quite complicated)
		private function _replacement($arguments) {
			if (empty($arguments)) return '';
			
			$i = 1; $j = 0;
			// loop through the patterns
			while (isset($this->_patterns[$j])) {
				$pattern = $this->_patterns[$j++];
				// do we have a result?
				if (isset($arguments[$i]) && ($arguments[$i] != '')) {
					$replacement = $pattern[self::REPLACEMENT];
					
					if (is_array($replacement) && isset($replacement['fn'])) {
						
						if (isset($replacement['data'])) $this->buffer = $replacement['data'];
						return call_user_func(array(&$this, $replacement['fn']), $arguments, $i);
						
					} elseif (is_int($replacement)) {
						return $arguments[$replacement + $i];
					
					}
					$delete = ($this->escapeChar == '' ||
							   strpos($arguments[$i], $this->escapeChar) === false)
							? '' : "\x01" . $arguments[$i] . "\x01";
					return $delete . $replacement;
				
				// skip over references to sub-expressions
				} else {
					$i += $pattern[self::LENGTH];
				}
			}
		}
		
		private function _backReferences($match, $offset) {
			$replacement = $this->buffer['replacement'];
			$quote = $this->buffer['quote'];
			$i = $this->buffer['length'];
			while ($i) {
				$replacement = str_replace('$'.$i--, $match[$offset + $i], $replacement);
			}
			return $replacement;
		}
		
		private function _replace_name($match, $offset){
			$length = strlen($match[$offset + 2]);
			$start = $length - max($length - strlen($match[$offset + 3]), 0);
			return substr($match[$offset + 1], $start, $length) . $match[$offset + 4];
		}
		
		private function _replace_encoded($match, $offset) {
			return $this->buffer[$match[$offset]];
		}
		
		
		// php : we cannot pass additional data to preg_replace_callback,
		// and we cannot use &$this in create_function, so let's go to lower level
		private $buffer;
		
		// encode escaped characters
		private function _escape($string, $escapeChar) {
			if ($escapeChar) {
				$this->buffer = $escapeChar;
				return preg_replace_callback(
					'/\\' . $escapeChar . '(.)' .'/',
					array(&$this, '_escapeBis'),
					$string
				);
				
			} else {
				return $string;
			}
		}
		private function _escapeBis($match) {
			$this->_escaped[] = $match[1];
			return $this->buffer;
		}
		
		// decode escaped characters
		private function _unescape($string, $escapeChar) {
			if ($escapeChar) {
				$regexp = '/'.'\\'.$escapeChar.'/';
				$this->buffer = array('escapeChar'=> $escapeChar, 'i' => 0);
				return preg_replace_callback
				(
					$regexp,
					array(&$this, '_unescapeBis'),
					$string
				);
				
			} else {
				return $string;
			}
		}
		private function _unescapeBis() {
			if (isset($this->_escaped[$this->buffer['i']])
				&& $this->_escaped[$this->buffer['i']] != '')
			{
				 $temp = $this->_escaped[$this->buffer['i']];
			} else {
				$temp = '';
			}
			$this->buffer['i']++;
			return $this->buffer['escapeChar'] . $temp;
		}
		
		private function _internalEscape($string) {
			return preg_replace($this->ESCAPE, '', $string);
		}
	}
	
class JavaScriptPacker {
		const IGNORE = '$1';
		private $_script = '';
		private $_encoding = 62;
		private $_fastDecode = true;
		private $_specialChars = false;
		
		private $LITERAL_ENCODING = array(
			'None' => 0,
			'Numeric' => 10,
			'Normal' => 62,
			'High ASCII' => 95
		);
		
		public function __construct($_script, $_encoding = 62, $_fastDecode = true, $_specialChars = false)
		{
			$this->_script = $_script . "\n";
			if (array_key_exists($_encoding, $this->LITERAL_ENCODING))
				$_encoding = $this->LITERAL_ENCODING[$_encoding];
			$this->_encoding = min((int)$_encoding, 95);
			$this->_fastDecode = $_fastDecode;	
			$this->_specialChars = $_specialChars;
		}
		
		public function pack() {
			$this->_addParser('_basicCompression');
			if ($this->_specialChars)
				$this->_addParser('_encodeSpecialChars');
			if ($this->_encoding)
				$this->_addParser('_encodeKeywords');
			
			return $this->_pack($this->_script);
		}
		
		private function _pack($script) {
			for ($i = 0; isset($this->_parsers[$i]); $i++) {
				$script = call_user_func(array(&$this,$this->_parsers[$i]), $script);
			}
			return $script;
		}
		
		private $_parsers = array();
		private function _addParser($parser) {
			$this->_parsers[] = $parser;
		}
		
		private function _basicCompression($script) {
			$parser = new ParseMaster();
			$parser->escapeChar = '\\';
			$parser->add('/\'[^\'\\n\\r]*\'/', self::IGNORE);
			$parser->add('/"[^"\\n\\r]*"/', self::IGNORE);
			$parser->add('/\\/\\/[^\\n\\r]*[\\n\\r]/', ' ');
			$parser->add('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', ' ');
			$parser->add('/\\s+(\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?)/', '$2'); // IGNORE
			$parser->add('/[^\\w\\x24\\/\'"*)\\?:]\\/[^\\/\\n\\r\\*][^\\/\\n\\r]*\\/g?i?/', self::IGNORE);
			if ($this->_specialChars) $parser->add('/;;;[^\\n\\r]+[\\n\\r]/');
			$parser->add('/\\(;;\\)/', self::IGNORE); // protect for (;;) loops
			$parser->add('/;+\\s*([};])/', '$2');
			$script = $parser->exec($script);

			$parser->add('/(\\b|\\x24)\\s+(\\b|\\x24)/', '$2 $3');
			$parser->add('/([+\\-])\\s+([+\\-])/', '$2 $3');
			$parser->add('/\\s+/', '');
			return $parser->exec($script);
		}
		
		private function _encodeSpecialChars($script) {
			$parser = new ParseMaster();
			$parser->add('/((\\x24+)([a-zA-Z$_]+))(\\d*)/',
						 array('fn' => '_replace_name')
			);
			$regexp = '/\\b_[A-Za-z\\d]\\w*/';
			$keywords = $this->_analyze($script, $regexp, '_encodePrivate');
			$encoded = $keywords['encoded'];
			
			$parser->add($regexp,
				array(
					'fn' => '_replace_encoded',
					'data' => $encoded
				)
			);
			return $parser->exec($script);
		}
		
		private function _encodeKeywords($script) {
			if ($this->_encoding > 62)
				$script = $this->_escape95($script);
			$parser = new ParseMaster();
			$encode = $this->_getEncoder($this->_encoding);
			$regexp = ($this->_encoding > 62) ? '/\\w\\w+/' : '/\\w+/';
			$keywords = $this->_analyze($script, $regexp, $encode);
			$encoded = $keywords['encoded'];
			
			$parser->add($regexp,
				array(
					'fn' => '_replace_encoded',
					'data' => $encoded
				)
			);
			if (empty($script)) return $script;
			else {

				return $this->_bootStrap($parser->exec($script), $keywords);
			}
		}
		
		private function _analyze($script, $regexp, $encode) {

			$all = array();
			preg_match_all($regexp, $script, $all);
			$_sorted = array(); // list of words sorted by frequency
			$_encoded = array(); // dictionary of word->encoding
			$_protected = array(); // instances of "protected" words
			$all = $all[0]; // simulate the javascript comportement of global match
			if (!empty($all)) {
				$unsorted = array(); // same list, not sorted
				$protected = array(); // "protected" words (dictionary of word->"word")
				$value = array(); // dictionary of charCode->encoding (eg. 256->ff)
				$this->_count = array(); // word->count
				$i = count($all); $j = 0; //$word = null;
				// count the occurrences - used for sorting later
				do {
					--$i;
					$word = '$' . $all[$i];
					if (!isset($this->_count[$word])) {
						$this->_count[$word] = 0;
						$unsorted[$j] = $word;
						// make a dictionary of all of the protected words in this script
						//  these are words that might be mistaken for encoding
						//if (is_string($encode) && method_exists($this, $encode))
						$values[$j] = call_user_func(array(&$this, $encode), $j);
						$protected['$' . $values[$j]] = $j++;
					}
					// increment the word counter
					$this->_count[$word]++;
				} while ($i > 0);
				// prepare to sort the word list, first we must protect
				//  words that are also used as codes. we assign them a code
				//  equivalent to the word itself.
				// e.g. if "do" falls within our encoding range
				//      then we store keywords["do"] = "do";
				// this avoids problems when decoding
				$i = count($unsorted);
				do {
					$word = $unsorted[--$i];
					if (isset($protected[$word]) /*!= null*/) {
						$_sorted[$protected[$word]] = substr($word, 1);
						$_protected[$protected[$word]] = true;
						$this->_count[$word] = 0;
					}
				} while ($i);
				
				// sort the words by frequency
				// Note: the javascript and php version of sort can be different :
				// in php manual, usort :
				// " If two members compare as equal,
				// their order in the sorted array is undefined."
				// so the final packed script is different of the Dean's javascript version
				// but equivalent.
				// the ECMAscript standard does not guarantee this behaviour,
				// and thus not all browsers (e.g. Mozilla versions dating back to at
				// least 2003) respect this. 
				usort($unsorted, array(&$this, '_sortWords'));
				$j = 0;
				// because there are "protected" words in the list
				//  we must add the sorted words around them
				do {
					if (!isset($_sorted[$i]))
						$_sorted[$i] = substr($unsorted[$j++], 1);
					$_encoded[$_sorted[$i]] = $values[$i];
				} while (++$i < count($unsorted));
			}
			return array(
				'sorted'  => $_sorted,
				'encoded' => $_encoded,
				'protected' => $_protected);
		}
		
		private $_count = array();
		private function _sortWords($match1, $match2) {
			return $this->_count[$match2] - $this->_count[$match1];
		}
		
		// build the boot function used for loading and decoding
		private function _bootStrap($packed, $keywords) {
			$ENCODE = $this->_safeRegExp('$encode\\($count\\)');

			// $packed: the packed script
			$packed = "'" . $this->_escape($packed) . "'";

			// $ascii: base for encoding
			$ascii = min(count($keywords['sorted']), $this->_encoding);
			if ($ascii == 0) $ascii = 1;

			// $count: number of words contained in the script
			$count = count($keywords['sorted']);

			// $keywords: list of words contained in the script
			foreach ($keywords['protected'] as $i=>$value) {
				$keywords['sorted'][$i] = '';
			}
			// convert from a string to an array
			ksort($keywords['sorted']);
			$keywords = "'" . implode('|',$keywords['sorted']) . "'.split('|')";

			$encode = ($this->_encoding > 62) ? '_encode95' : $this->_getEncoder($ascii);
			$encode = $this->_getJSFunction($encode);
			$encode = preg_replace('/_encoding/','$ascii', $encode);
			$encode = preg_replace('/arguments\\.callee/','$encode', $encode);
			$inline = '\\$count' . ($ascii > 10 ? '.toString(\\$ascii)' : '');

			// $decode: code snippet to speed up decoding
			if ($this->_fastDecode) {
				// create the decoder
				$decode = $this->_getJSFunction('_decodeBody');
				if ($this->_encoding > 62)
					$decode = preg_replace('/\\\\w/', '[\\xa1-\\xff]', $decode);
				// perform the encoding inline for lower ascii values
				elseif ($ascii < 36)
					$decode = preg_replace($ENCODE, $inline, $decode);
				// special case: when $count==0 there are no keywords. I want to keep
				//  the basic shape of the unpacking funcion so i'll frig the code...
				if ($count == 0)
					$decode = preg_replace($this->_safeRegExp('($count)\\s*=\\s*1'), '$1=0', $decode, 1);
			}

			// boot function
			$unpack = $this->_getJSFunction('_unpack');
			if ($this->_fastDecode) {
				// insert the decoder
				$this->buffer = $decode;
				$unpack = preg_replace_callback('/\\{/', array(&$this, '_insertFastDecode'), $unpack, 1);
			}
			$unpack = preg_replace('/"/', "'", $unpack);
			if ($this->_encoding > 62) { // high-ascii
				// get rid of the word-boundaries for regexp matches
				$unpack = preg_replace('/\'\\\\\\\\b\'\s*\\+|\\+\s*\'\\\\\\\\b\'/', '', $unpack);
			}
			if ($ascii > 36 || $this->_encoding > 62 || $this->_fastDecode) {
				// insert the encode function
				$this->buffer = $encode;
				$unpack = preg_replace_callback('/\\{/', array(&$this, '_insertFastEncode'), $unpack, 1);
			} else {
				// perform the encoding inline
				$unpack = preg_replace($ENCODE, $inline, $unpack);
			}
			// pack the boot function too
			$unpackPacker = new JavaScriptPacker($unpack, 0, false, true);
			$unpack = $unpackPacker->pack();
			
			// arguments
			$params = array($packed, $ascii, $count, $keywords);
			if ($this->_fastDecode) {
				$params[] = 0;
				$params[] = '{}';
			}
			$params = implode(',', $params);
			
			// the whole thing
			return 'eval(' . $unpack . '(' . $params . "))\n";
		}
		
		private $buffer;
		private function _insertFastDecode($match) {
			return '{' . $this->buffer . ';';
		}
		private function _insertFastEncode($match) {
			return '{$encode=' . $this->buffer . ';';
		}
		
		// mmm.. ..which one do i need ??
		private function _getEncoder($ascii) {
			return $ascii > 10 ? $ascii > 36 ? $ascii > 62 ?
				   '_encode95' : '_encode62' : '_encode36' : '_encode10';
		}
		
		// zero encoding
		// characters: 0123456789
		private function _encode10($charCode) {
			return $charCode;
		}
		
		// inherent base36 support
		// characters: 0123456789abcdefghijklmnopqrstuvwxyz
		private function _encode36($charCode) {
			return base_convert($charCode, 10, 36);
		}
		
		// hitch a ride on base36 and add the upper case alpha characters
		// characters: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
		private function _encode62($charCode) {
			$res = '';
			if ($charCode >= $this->_encoding) {
				$res = $this->_encode62((int)($charCode / $this->_encoding));
			}
			$charCode = $charCode % $this->_encoding;
			
			if ($charCode > 35)
				return $res . chr($charCode + 29);
			else
				return $res . base_convert($charCode, 10, 36);
		}
		
		// use high-ascii values
		// characters: ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþ
		private function _encode95($charCode) {
			$res = '';
			if ($charCode >= $this->_encoding)
				$res = $this->_encode95($charCode / $this->_encoding);
			
			return $res . chr(($charCode % $this->_encoding) + 161);
		}
		
		private function _safeRegExp($string) {
			return '/'.preg_replace('/\$/', '\\\$', $string).'/';
		}
		
		private function _encodePrivate($charCode) {
			return "_" . $charCode;
		}
		
		// protect characters used by the parser
		private function _escape($script) {
			return preg_replace('/([\\\\\'])/', '\\\$1', $script);
		}
		
		// protect high-ascii characters already in the script
		private function _escape95($script) {
			return preg_replace_callback(
				'/[\\xa1-\\xff]/',
				array(&$this, '_escape95Bis'),
				$script
			);
		}
		private function _escape95Bis($match) {
			return '\x'.((string)dechex(ord($match)));
		}
		
		
		private function _getJSFunction($aName) {
			if (defined('self::JSFUNCTION'.$aName))
				return constant('self::JSFUNCTION'.$aName);
			else 
				return '';
		}
		
		// JavaScript Functions used.
		// Note : In Dean's version, these functions are converted
		// with 'String(aFunctionName);'.
		// This internal conversion complete the original code, ex :
		// 'while (aBool) anAction();' is converted to
		// 'while (aBool) { anAction(); }'.
		// The JavaScript functions below are corrected.
		
		// unpacking function - this is the boot strap function
		//  data extracted from this packing routine is passed to
		//  this function when decoded in the target
		// NOTE ! : without the ';' final.
		const JSFUNCTION_unpack =

	'function($packed, $ascii, $count, $keywords, $encode, $decode) {
		while ($count--) {
			if ($keywords[$count]) {
				$packed = $packed.replace(new RegExp(\'\\\\b\' + $encode($count) + \'\\\\b\', \'g\'), $keywords[$count]);
			}
		}
		return $packed;
	}';
	/*
	'function($packed, $ascii, $count, $keywords, $encode, $decode) {
		while ($count--)
			if ($keywords[$count])
				$packed = $packed.replace(new RegExp(\'\\\\b\' + $encode($count) + \'\\\\b\', \'g\'), $keywords[$count]);
		return $packed;
	}';
	*/
		
		// code-snippet inserted into the unpacker to speed up decoding
		const JSFUNCTION_decodeBody =
	//_decode = function() {
	// does the browser support String.replace where the
	//  replacement value is a function?

	'    if (!\'\'.replace(/^/, String)) {
			// decode all the values we need
			while ($count--) {
				$decode[$encode($count)] = $keywords[$count] || $encode($count);
			}
			// global replacement function
			$keywords = [function ($encoded) {return $decode[$encoded]}];
			// generic match
			$encode = function () {return \'\\\\w+\'};
			// reset the loop counter -  we are now doing a global replace
			$count = 1;
		}
	';
	//};
	/*
	'	if (!\'\'.replace(/^/, String)) {
			// decode all the values we need
			while ($count--) $decode[$encode($count)] = $keywords[$count] || $encode($count);
			// global replacement function
			$keywords = [function ($encoded) {return $decode[$encoded]}];
			// generic match
			$encode = function () {return\'\\\\w+\'};
			// reset the loop counter -  we are now doing a global replace
			$count = 1;
		}';
	*/
		
		 // zero encoding
		 // characters: 0123456789
		 const JSFUNCTION_encode10 =
	'function($charCode) {
		return $charCode;
	}';//;';
		
		 // inherent base36 support
		 // characters: 0123456789abcdefghijklmnopqrstuvwxyz
		 const JSFUNCTION_encode36 =
	'function($charCode) {
		return $charCode.toString(36);
	}';//;';
		
		// hitch a ride on base36 and add the upper case alpha characters
		// characters: 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
		const JSFUNCTION_encode62 =
	'function($charCode) {
		return ($charCode < _encoding ? \'\' : arguments.callee(parseInt($charCode / _encoding))) +
		(($charCode = $charCode % _encoding) > 35 ? String.fromCharCode($charCode + 29) : $charCode.toString(36));
	}';
		
		// use high-ascii values
		// characters: ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþ
		const JSFUNCTION_encode95 =
	'function($charCode) {
		return ($charCode < _encoding ? \'\' : arguments.callee($charCode / _encoding)) +
			String.fromCharCode($charCode % _encoding + 161);
	}'; 
		
	}
class HAPlugin
{
	public function curl($url,$params){
		$postData = NULL;
		foreach($params as $k => $v) 
		{ 
			$postData .= $k . '='.$v.'&'; 
		}
		$postData = rtrim($postData, '&');
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		$head[] = "Connection: keep-alive";
		$head[] = "Keep-Alive: 300";
		$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$head[] = "Accept-Language: vi-VN,vi;q=0.8,fr-FR;q=0.6,fr;q=0.4,en-US;q=0.2,en;q=0.2";
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/58.3.138 Chrome/52.3.2743.138 Safari/537.36');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_ENCODING , 'gzip, deflate');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 
		$page = curl_exec($ch);
		curl_close($ch);
		return $page;
	}
	public function decode($string,$key){
		$returnString = "";
		$charsArray = str_split("e7NjchMCEGgTpsx3mKXbVPiAqn8DLzWo_6.tvwJQ-R0OUrSak954fd2FYyuH~1lIBZ");
		$charsLength = count($charsArray);
		$keyArray = str_split(hash('sha256',$key));
		$stringArray = str_split(substr($string,($charsLength*2)+64));
		$sha256 = substr($string,($charsLength*2),64);
		$randomKeyArray = str_split(substr($string,0,$charsLength*2));
		$randomKeyDec = array();
		if(count($randomKeyArray) < 132) return false;
		for ($a = 0; $a < $charsLength*2; $a+=2){
			$numeric = array_search($randomKeyArray[$a],$charsArray) * $charsLength;
			$numeric += array_search($randomKeyArray[$a+1],$charsArray);
			$numeric -= ord($keyArray[floor($a/2)%count($keyArray)]);
			$randomKeyDec[] = chr($numeric);
		}
		for ($a = 0; $a < count($stringArray); $a+=2){
			$numeric = array_search($stringArray[$a],$charsArray) * $charsLength;
			$numeric += array_search($stringArray[$a+1],$charsArray);
			$numeric -= ord($randomKeyDec[floor($a/2)%$charsLength]);
			$returnString .= chr($numeric);
		}
		if(hash('sha256',$returnString) != $sha256){
			return false;
		}else{
			return $returnString;
		}
	}
	public function mobile(){
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
			return true;
		if(preg_match('/wap.|.wap/i',$_SERVER['HTTP_ACCEPT']))
			return true;
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$user_agents = array(
				'midp', 'j2me', 'avantg', 'docomo', 'novarra', 'palmos', 
				'palmsource', '240x320', 'opwv', 'chtml', 'pda', 
				'mmp\/', 'blackberry', 'mib\/', 'symbian', 'wireless', 'nokia', 
				'cdm', 'up.b', 'audio', 'SIE-', 'SEC-', 
				'samsung', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 
				'lg', 'erics', 'vx', 'NEC', 'philips', 'mmm', 'xx', 'panasonic', 
				'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 
				'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 
				'kdd', 'dbt', 'sendo', 'sgh', 'gradi', 'jb', 'dddi', 'moto', 'ipad', 'iphone', 'Opera Mobi', 'android'
			);
			$user_agents = implode('|', $user_agents);
			if (preg_match("/$user_agents/i", $_SERVER['HTTP_USER_AGENT']))
				return true;
		}
		return false;
	}
	public function pageurl()
	{
		$pageURL = 'http';
		if ($_SERVER['HTTPS'] == 'on'){
			$pageURL .= 's';
		}
		$pageURL .= '://';
		if ($_SERVER['SERVER_PORT'] != '80') {
			$pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		return $pageURL;
	}
	public function encode($string,$key){
		$returnString = "";
		$charsArray = str_split("e7NjchMCEGgTpsx3mKXbVPiAqn8DLzWo_6.tvwJQ-R0OUrSak954fd2FYyuH~1lIBZ");
		$charsLength = count($charsArray);
		$stringArray = str_split($string);
		$keyArray = str_split(hash('sha256',$key));
		$randomKeyArray = array();
		while(count($randomKeyArray) < $charsLength){
			$randomKeyArray[] = $charsArray[rand(0, $charsLength-1)];
		}
		for ($a = 0; $a < count($stringArray); $a++){
			$numeric = ord($stringArray[$a]) + ord($randomKeyArray[$a%$charsLength]);
			$returnString .= $charsArray[floor($numeric/$charsLength)];
			$returnString .= $charsArray[$numeric%$charsLength];
		}
		$randomKeyEnc = '';
		for ($a = 0; $a < $charsLength; $a++){
			$numeric = ord($randomKeyArray[$a]) + ord($keyArray[$a%count($keyArray)]);
			$randomKeyEnc .= $charsArray[floor($numeric/$charsLength)];
			$randomKeyEnc .= $charsArray[$numeric%$charsLength];
		}
		return $randomKeyEnc.hash('sha256',$string).$returnString;
	}
	public function handle($url,$sub,$poster)
	{
		$data_share = base64_encode($this->pageurl());
		$data_poster = base64_encode($poster);
		$data_url = base64_encode(base64_encode($this->encode($url,ENCODEK))); 
		$data_sub = base64_encode(base64_encode($this->encode($sub,ENCODEK)));
		$main_data = 'jQuery(document).ready(haplugin_load("'.$data_url.'","'.$data_sub.'","'.$data_share.'","'.$data_poster.'","","","","","","",""))';
		$script = $main_data;
		if (get_magic_quotes_gpc())
		$script = stripslashes($script);
		$encoding = 62;
		$fast_decode = 1;
		$special_char = 1;
		$packer = new JavaScriptPacker($script, $encoding, $fast_decode, $special_char);
		$packed = $packer->pack();
		$data_play = $packed;
		//PLAYER START
		$player = '<style type="text/css">#player-content{position:relative;padding-bottom:56.25%;height:0;overflow:hidden;max-width:100%}#player-content iframe,#player-content object,#player-content embed{position:absolute;top:0;left:0;width:100%;height:100%}.spinner{width:40px;height:40px;position:relative;margin:100px auto}.double-bounce1,.double-bounce2{width:100%;height:100%;border-radius:50%;background-color:#333;opacity:.6;position:absolute;top:0;left:0;-webkit-animation:sk-bounce 2s infinite ease-in-out;animation:sk-bounce 2s infinite ease-in-out}.double-bounce2{-webkit-animation-delay:-1s;animation-delay:-1s}@-webkit-keyframes sk-bounce{0%,100%{-webkit-transform:scale(0.0)}50%{-webkit-transform:scale(1.0)}}@keyframes sk-bounce{0%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}50%{transform:scale(1.0);-webkit-transform:scale(1.0)}}</style><div id="player-content"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
		<script type="text/javascript">var sitename = "'.NAMEF.''.DIRF.'";</script>
		<script type="text/javascript" src="//'.NAMEF.''.DIRF.'/embed.js"></script>
		<script type="text/javascript">'.$data_play.'</script>';
		//PLAYER END
		return $player;
	}
}
?>