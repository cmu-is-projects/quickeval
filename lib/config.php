<?php
/**
 * This file contains the site configuration for the QuickEval Site
 *
 * @author Ari Rubinstein
 * @package QuickEval
 **/

if (strtolower($_SERVER['SERVER_NAME']) == "www.quickeval.me"){
	define("ENVIRONMENT", "Production");
} else {
	define("ENVIRONMENT", "Development");
}

require_once("Util/Spyc.php");
$yaml = Spyc::YAMLLoad(dirname(__FILE__).'/config.yaml');

foreach ($yaml['Database'][ENVIRONMENT] as $k=>$v){
	define("DB_".strtoupper($k), $v);
}
foreach (array_merge($yaml["Configuration"][ENVIRONMENT], $yaml["Configuration"]["Globals"]) as $k=>$v){
	define(strtoupper($k), $v);
}
define("TEMPLATE_DIRECTORY", SITE_ROOT . "templates/");
define('DEFAULT_AVATAR', WEB_ROOT.'images/default.png');

if (array_key_exists("HTTP_HOST", $_SERVER)){
	define('COMMAND_LINE', false);
} else {
	define('COMMAND_LINE', true);
}


//FirePHP Library for Console Logging
require_once('FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true);
require_once('FirePHPCore/fb.php');
ob_start();
if (ENVIRONMENT == "Production"){
	//TODO: CHANGE THIS TO FALSE
	$firephp->setEnabled(true);
} else {
	$firephp->setEnabled(true);
}
//End FirePHP Logging


require_once("bootstrap.php");

?>