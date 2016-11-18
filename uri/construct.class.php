<?php
/**
 * FileName: index.php
 * Description: construct class & autoloader
 * Author: kim
 * Date: 2013-4-7 10:34:08
 * Version: 2.00
 **/
namespace construct;

use construct\Uri;

/**
* loader necessary class or function and configuration
*/
class loader 
{
	private $starTime;
	public function __construct($config)
	{
		$this->initHelper();
		if(!is_array($config)){
			throw new \Exception("config is not array type");
		}
		(new \Uri\uriParser())->run($config);
	}

	private function initHelper(){
		$this->starTime = $this->getMillisecond();
		set_error_handler(array($this,"errorHandle"));
		set_exception_handler(array($this,'exceptionHandle'));
		register_shutdown_function(array($this,'endHandle'));
	}

	public function errorHandle(){
		echo error_get_last();
		// var_dump($_SERVER);
	}

	public function exceptionHandle($exception){
		echo "exception message: " , $exception->getMessage(), "/n";  
		
	}

	public function endHandle(){
		// $endTime = $this->getMillisecond();
		// print "Stat time :".$this->starTime;
		// print "Stat time :".$endTime;
		// print "Time : ".($endTime - $this->starTime);
	}

	private function getMillisecond() { 
		list($s1, $s2) = explode(' ', microtime()); 
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000); 
	}



}