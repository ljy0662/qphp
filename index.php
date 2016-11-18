<?php
/**
 * FileName: index.php
 * Description: Entry file
 * Author: kim
 * Date: 2013-4-7 10:34:08
 * Version: 2.00
 **/


define('RootPath',dirname(__FILE__).'/');
require RootPath . 'vendor/autoload.php';
require_once(RootPath.'uri/construct.class.php');
$config = require_once(RootPath.'config.inc.php');
new \construct\loader($config);
?>