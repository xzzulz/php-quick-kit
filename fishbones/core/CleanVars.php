<?php
/**
 * fishbones microframework
 *
 * data sanitization 
 *
 */


/////////////////////////////////////////////////////////////////////////////////////


/**
 * data sanitization class CleanVars
 *
 * fishbones.core
 */
class CleanVars {


	public function __construct() {
	
	}

	/**
	 * return var escaped to prevent database injection attacks.
	 * 
	 * @param $par string string variable to clean
	 */	
	public function clean( $par ) {

		// temporal
		// remove any & character
		//$par = str_replace( '&', '', $par );
	
		//Stripslashes
		if( get_magic_quotes_gpc() ) {
			$par = stripslashes( $par );
		}
		
		//Quote
		$conex = Fishbones::getDB()->conex;
		$val = mysql_real_escape_string( $par, $conex );
		
		return $val;
	}
	
	
	/**
	 * return arrays escaped to prevent database injection attacks.
	 * 
	 * @param $arrl array array of string variables to clean
	 */	
	public function cleanArray( $arr ) {
				
		foreach ( $arr as $dato => $val ) {
			$arr[$dato] = $this->clean( $val );	
		}
				
		return $arr;
	}
	
	/**
	 * cleans POST and GET vars
	 */	
	public function cleanHttpRequestVars() {
		
		$_GET = $this->cleanArray( $_GET );
		$_POST = $this->cleanArray( $_POST );

	}

}
?>