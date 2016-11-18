<?php

/**
 * FileName: config.inc.php
 * Description: 项目配置文件
 * Author: kim
 * Date: 2016-11-16 15:47:41
 * Version: 2.00
 **/
return [
	'debug'=>1,
	'ss'=>"ssh2_fingerprint(session)",
	'redis'=>
	 		[
	 		'scheme' => 'tcp',
		    'host'   => '127.0.0.1',
		    'port'   => 6379
		    ]
];
