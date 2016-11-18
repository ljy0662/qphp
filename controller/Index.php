<?php


namespace Controller;

use \uri\view;
use \Predis\Client as predis;

/**
* controller
*/
class index extends View
{
	

	public $c ;
	private $config;
	function __construct($config)
	{
		try {
			$this->client = new predis($config['redis']);
		} catch (Exception $e) {
			echo 'connect fail';
		}
		
	}

	function index(){



		$this->c  = phpinfo();
		$this->tempalte();
		echo 3;
	}

	private function test(){
		echo 4;
	}

	public function server(){
		print_r($_SERVER);
	}

	public function testRedis(){

		
		$this->client->set('foo', 'bar1111');
		$value = $this->client->get('foo');
		echo $value;
	}
}