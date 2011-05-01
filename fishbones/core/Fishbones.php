<?php
/**
 * fishbones microframework
 *
 * instances locator
 *
 */


/**
 * class Fishbones
 *
 * locator for core classes
 */
class FishBones {
	
	
	
	// database instance
	private static $db;
	// config instance
	private static $config;
	// log instance
	private static $log;
	// output preparation and emitting class;
	private static $pump;
	// database variables protection
	private static $cleanParams;
	// fishbones instance
	private static $fishbones;


	private function __construct() {

	}



	/**
	 * The clone method prevents external instantiation of copies
	 *
	 */
	private function __clone() {

	}





	/**
	 * get the Log instance
	 *
	 * @return Log return the common Database class instance
	 */
	static function getLog() {
		if (!self::$log instanceof Log) {
			self::$log = new Log();
		}
		return self::$log;
	}



	/**
	 * get the Database instance
	 *
	 * @return DB return the common Database class instance
	 */
	static function getDB() {
		if (!self::$db instanceof DB) {
			self::$db = new DB();
		}
		return self::$db;
	}

	
	/**
	 * get the Config class object instance
	 *
	 * @return Config the config class object instance
	 */
	static function getConfig() {
		if (!self::$config instanceof Config) {
			self::$config = new Config();
		}
		return self::$config;
	}


	/**
	 * get the Pump instance
	 *
	 * @return Pump return the Pump class instance
	 */
	static function getPump() {
		if (!self::$pump instanceof Pump) {
			self::$pump = new Pump();
		}
		return self::$pump;
	}



	/**
	 * get the CleanVars instance
	 *
	 * @return Pump return the CleanVars class instance
	 */
	static function getCleanVars() {
		if (!self::$cleanParams instanceof CleanVars) {
			self::$cleanParams = new CleanVars();
		}
		return self::$cleanParams;
	}	
	
	
	

	/**
	 * get the fishbones instance
	 *
	 * @return fishbones return the fishbones class instance
	 */
	static function getFishbones() {
		if (!self::$fishbones instanceof Fishbones) {
			self::$fishbones = new Fishbones();
		}
		return self::$fishbones;
	}



}


?>