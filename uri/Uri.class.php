<?php
/**
 * FileName: index.php
 * Description: construct class & autoloader
 * Author: kim
 * Date: 2013-4-7 10:34:08
 * Version: 2.00
 **/
namespace Uri;

use Exception;
// use \controller\index\index;

define(CONTROLLER, '\Controller'); #same with composer's namespace
define(VIEW, 'view/'); #configuration View's file path
define(SUFFIX, '.php'); #configuration View's postfix name ,such as .php .tpl
define(ROUTE,'r'); #define route key name 
/**
* uri deal
*/

class uriParser 
{
	private $route;
	private $routeArry;
	static $className;
	static $funcName;
	function __construct()
	{
		$this->route = isset($_GET[ROUTE]) ? $_GET[ROUTE] :'index/index';
		if(!strpos($this->route,'/'))
			$this->route.='/index';
		$this->strParser();
	}

	private function strParser(){
		$tempArray = explode('/', $this->route);
		$this->className = $tempArray[0];
		$this->funcName = $tempArray[1];
		if($this->funcName =='')
			$this->funcName = 'index';
		uriParser::$funcName=$this->funcName;
	}

	public function run($config){
		if(!class_exists( CONTROLLER.'\\'.$this->className ))
			throw new Exception("Error clase not found on the <b>\Controller: {$this->className}</b>");
		if(!method_exists (CONTROLLER.'\\'.$this->className , $this->funcName))
			throw new Exception("Error class's function not found on the <b>\Controller: {$this->className}/{$this->funcName}</b>");
		$abc = CONTROLLER.'\\'.$this->className;
		$a = $this->funcName;
		(new $abc($config))->$a();

	}

}



/**
 * @description: tools class
 * @author: tonyshenlong@gmail.com
 * @charset: UTF-8
 * @time: 2016-11-18 14:20:53
 * @version 2.0
**/
class Utils
{
/**
 * @name: get_var_get
 * @description:  get GET's value
 * @param: string $_GET' key
 * @param: boolean is used filter rule
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function Get($var_name, $is_filter=TRUE){
	$return = isset($_GET[$var_name]) ? $_GET[$var_name] : NULL;
	if($is_filter && !$this->is_empty($return)) $return = $this->filter_string($return);
	return $return;
}

/**
 * @name: get_var_post
 * @description: get POST's value
 * @param: string $_POST' key
 * @param: boolean  is used filter rule
 * @return: mixed
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function Post($var_name, $is_filter=TRUE){
	$return = isset($_POST[$var_name]) ? $_POST[$var_name] : NULL;
	if($is_filter && !$this->is_empty($return)) $return = $this->filter_string($return);
	return $return;
}

/**
 * @name: is_empty
 * @description: check the variable is the empty
 * @param: mixed string
 * @return: boolean
 * @author: Kim
 * @create: 2012-05-04 14:26:50
**/
function is_empty($var_name){
	$return = FALSE;
	!isset($var_name) && $return = TRUE;
	if(!$return){
		switch(strtolower(gettype($var_name))){
			case 'null' 	:{ $return = TRUE;BREAK; }
			case 'integer' 	:{ $return = FALSE;BREAK; }
			case 'double' 	:{ $return = FALSE;BREAK; }
			case 'boolean' 	:{ $return = FALSE;BREAK; }
			case 'string' 	:{ $return = $var_name==='' ? TRUE : FALSE;BREAK; }
			case 'array' 	:{ $return = count($var_name) > 0 ? FALSE : TRUE;BREAK; }
			case 'object' 	:{ $return = $var_name===null ? TRUE : FALSE;BREAK; }
			case 'resource' :{ $return = $var_name===null ? TRUE : FALSE;BREAK; }
			default 		:{ $return = TRUE; }
		}
	}
	return $return;
}

	/**
	 * @name: filter_string
	 * @description: filter Special char
	 * @param: mixed  support string or array
	 * @return: mixed
	 * @author: Kim
	 * @create: 2012-05-04 14:26:50
	**/
	function filter_string($string){
		if(is_empty($string)) return '';
		if(is_array($string)){
			foreach($string as $key => $val) $string[$key] = $this->filter_string($val);
			return $string;
		}else{
			$search = array("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n])[\s]+'", "'&(quot|#34);'i", "'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i", "'&(pound|#163);'i", "'&(copy|#169);'i", "'&#(\d+);'e");
			$replace = array("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");
			return trim(addslashes(nl2br(stripslashes(preg_replace($search, $replace, $string)))));
		}
	}

	/**
	 * @name: sql_normalize
	 * @description: deal Mysql tag
	 * @param: string 
	 * @return: string
	 * @author: Kim
	 * @create: 2012-05-04 14:26:50
	**/
	function sql_normalize($sql){
		$sql = preg_replace("/\\/\\*.*\\*\\//sU", '', $sql); 						// remove multiline comments
		$sql = preg_replace("/([\"'])(?:\\\\.|\"\"|''|.)*\\1/sU", "{}", $sql); 		// remove quoted strings
		$sql = preg_replace("/(\\W)(?:-?\\d+(?:\\.\\d+)?)/", "\\1{}", $sql); 		// remove numbers
		$sql = preg_replace("/(\\W)null(?:\\Wnull)*(\\W|\$)/i", "\\1{}\\2", $sql); 	// remove nulls
		$sql = str_replace(array("\\n", "\\t", "\\0"), ' ', $sql); 					// replace escaped linebreaks
		$sql = preg_replace("/\\s+/", ' ', $sql); 									// remove multiple spaces
		$sql = preg_replace("/ (\\W)/", "\\1", $sql); 								// remove spaces bordering with non-characters
		$sql = preg_replace("/(\\W) /", "\\1", $sql); 								// --,--
		$sql = preg_replace("/\\{\\}(?:,?\\{\\})+/", "{}", $sql); 					// repetitive {},{} to single {}
		$sql = preg_replace("/\\(\\{\\}\\)(?:,\\(\\{\\}\\))+/", "({})", $sql); 		// repetitive ({}),({}) to single ({})
		$sql = strtolower(trim($sql, " \t\n)(")); 									// trim spaces and strolower
		return $sql;
	}

}


/**
 * @description: View class
 * @author: tonyshenlong@gmail.com
 * @charset: UTF-8
 * @time: 2016-11-18 14:20:53
 * @version 2.0
**/
class View extends Utils
{
	

	function tempalte($fileName){
		if(!$fileName)
			$fileName = uriParser::$funcName;
		$filePath = VIEW.$fileName.SUFFIX;
		if(is_file($fileName))
			include $filePath;
		else
			throw new Exception("Cannot found the tempalte on this path :".$filePath, 1);
	}
}