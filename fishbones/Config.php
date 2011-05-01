<?php
/**
 * fishbones microframework
 *
 * Configuration class Config
 * 
 * @package fishbones
 */


/////////////////////////////////////////////////////////////////////////////////////



/**
 * Simple class for configuration data
 */	
class Config {
	
	
	
	const MODE_DEVELOPMENT = 'development';
	const MODE_PRODUCTION = 'production';
	
	
	public $mode = Config::MODE_DEVELOPMENT;
	
	/////////////////////////////////////////////////////////////////////////////////////////


	// place config vars here
	
	
	///////////////////////////////   d a t a b a s e   ///////////////////////////////
	
	// database config vars
	public $dbUser = '';
	public $dbPass = '';
	public $dbHost = '';
	public $dbDatabase = '';
	

	
	///////////////////////////////   s e c u r i t y   ///////////////////////////////
	
	// WARNING
	// security requirement
	// move the fishbones folder to a non internet accesible folder, and set the path 
	// on the      -  start.php  -       file, for production sites.

	
	// automatic escape GET and POST vars
	//	
	// Setting this to true, makes all GET and POST vars
	// to be escaped with mysql_real_escape_string() 
	// WARNING
	// if this is set to false, remember to manually clean your incoming data
	public $autoEscapeHttpRequestVars = true;


	///////////////////////////////   logs  ///////////////////////////////
	
	// write log file for debugging
	public $debugLog = true;
	
	
	
	////////////////////////////////////////////////////////////////////////
	// path
	
	// leave these as empty strings here
	public $currentPathToFishbones = '';
	public $currentPathToStart = '';
	
}

?>