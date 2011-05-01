<?php
/**
 * fishbones microframework
 *
 * logging
 *
 */


/**
 * class Log
 * 
 * write data to log file
 * 
 * For use duringn development only
 * Logging should be disabled for production
 *
 */
class Log {
	

	// particular methods config
	public $writeErrors = true;
	public $writeSqls = true;
	public $writeDatabaseErrors = true;
	public $writeOutputs = true;
	public $writeDebugs = true;
	
	// clear log file at the start of script
	public $clearAtStart = false;
	
	// error status data	
	public $error = false;
	
	// max log size in bytes
	public $maxSize = 20000;
	public $keepMaxSize = 20000;
	
	private static $instance;
	private $filename = "runlog.txt";
	private $file = false;
	
	private $keepfilename = "keep";
	


	/**
	 * constructor
	 */
	public function __construct() {

	}
 



	/**
	 * clone method 
	 */
	private function __clone() {
    
	}

	
	
	/**
	 * get instance
	 */
	public static function getInstance() {
		if (!self::$instance instanceof self) { 
			self::$instance = new self;
			
		}
		return self::$instance;
	}
	

	
	/**
	 * write an error text to log file
	 * 
	 * @param $errorString string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeError( $errorString ) {
		
		if( !$this->writeErrors ) { 
			return true;
		}
		$this->write( "error:\n----------------------------\n" . $errorString );
		return true;
	}

	

	/**
	 * used to write php output text to log file
	 * 
	 * @param $errorString string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeOutput( $outputString ) {
	
		if( !$this->writeOutputs ) { 
			return true;
		}
		
		$this->write( "PHP output:\n-----------------------\n" . $outputString );
		return true;
	}	
	
	
	
	/**
	 * write an error text to log file
	 * 
	 * @param $sqlString string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeSql( $sqlString ) {
	
		if( !$this->writeSqls ) { 
			return true;
		}
		
		$this->write( "SQL query:\n------------------------\n" . $sqlString );
		return true;
	}

	
	
	/**
	 * write an database error text to log file
	 * 
	 * @param $databaseError string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeDatabaseError( $databaseError ) {
	
		if( !$this->databaseErrors ) { 
			return true;
		}
		
		$this->write( "Database error :\n-------------------\n" . $databaseError );
		return true;
	}
	
	
	
	/**
	 * write debug data to log file
	 * 
	 * @param $debugData string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeDebug( $debugData ) {
		
		if( !$this->writeDebugs ) { 
			return true;
		}
		
		$this->write( "debug data:\n-----------------------\n" . $debugData );
		return true;
	}	
	

	/**
	 * write debug variable to log file
	 * 
	 * @param $debugData string text to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeDebugVar( $debugVar ) {
		
		if( !$this->writeDebugs ) { 
			return true;
		}
		
		$this->write( "debug variable:\n--------------------\n" . print_r($debugVar, true) );
		return true;
	}

	
	
	/**
	 * write debug variable to log file
	 * 
	 * @param $XMLDom DOMDocument xml to be written into the log
	 * @return boolean true on sucess, false on error
	 */ 
	public function writeXMLDomDoc( $XMLDom ) {
		
		if( !$this->writeDebugs ) { 
			return true;
		}
		
		$this->write( "debug DOM XML Document:\n--------------------\n" . print_r($XMLDom->saveXML(), true) );
		return true;
	}
	
	
	
	
	/**
	 * write text to log file
	 * 
	 * @param $tex string writes string into log file
	 * @return boolean true on sucess, false on error
	 */ 
	public function write( $text ) {
		
		if( ! (Fishbones::getConfig()->debugLog ) ) { 
			return true;
		}
		
		if( !$this->file ) {
			$this->openLogFile();
		}
		
		if ( !$this->file ) {
			$this->error = "<error>error opening log file</error>\n";
			return false;
		}
		
		$markedText = "------------------------------------------------------------------\n";
		$markedText .= date('d/m/Y   H:i:s')."   ". $_SERVER['SCRIPT_NAME']."\n";
		$markedText .= $text."\n" ;
		
		$write = fwrite( $this->file, $markedText, 2000 );

		if ( !$write ) {
			$this->error = "<error>error writing log file</error>\n";
			return false;
		}

		return true;
	}





	
	/**
	 * opens log file
	 * 
	 * opens log file, if clearAtStart is true, also clear it.
	 * @return boolean true on sucess, false on error
	 */
	public function openLogFile() {	
		
		if( ! (Fishbones::getConfig()->debugLog ) ) { 
			return true;
		}
		
		// create logs directory if it don't exists
		$logsDir = Fishbones::getConfig()->currentPathToStart .
				Fishbones::getConfig()->currentPathToFishbones .
				'logs';
		if( ! file_exists( $logsDir ) ) {
			mkdir( $logsDir, 0777 );
		}
		
		if( $this->clearAtStart ) {
			//$this->file = fopen( $this->filename, 'wt' );
			$this->file = fopen( Fishbones::getConfig()->currentPathToStart .
				Fishbones::getConfig()->currentPathToFishbones .
				'logs/' .
				$this->filename, 'wt' );


		} else {
			
			$logCurrentSize = filesize(
				Fishbones::getConfig()->currentPathToStart .
				Fishbones::getConfig()->currentPathToFishbones .
				'logs/' .
				$this->filename
			);
			
			
			$this->file = fopen( Fishbones::getConfig()->currentPathToStart .
				Fishbones::getConfig()->currentPathToFishbones .
				'logs/' .
				$this->filename, 'at' );
				//$this->file = fopen( $this->filename, 'at' );
			
			if( $logCurrentSize > $this->maxSize ) {
				copy(
					Fishbones::getConfig()->currentPathToStart .
					Fishbones::getConfig()->currentPathToFishbones .
					'logs/' .
					$this->filename,
					Fishbones::getConfig()->currentPathToStart .
					Fishbones::getConfig()->currentPathToFishbones .
					'logs/' .
					'otherLog'
				);
				ftruncate( $this->file, 0 );
			}
			
		}
		
		if ( $this->file ) {
			return true;
		} else {
			return false;
		}
		
	}




	
	
	/**
	 * clears log file
	 * @return boolean true on sucess, false on error
	 */
	public function clearLog() {
				
		if( $this->file ) {
			$clo = fclose( $this->file );
		}
		
		if( !$clo ) {
			$this->error = "<error>error clearing log file</error>\n";
			return false;			
		}
		
		$del = ftruncate( $this->file, 0 );
		
		if ( !$del ) {
			$this->error = "<error>error clearing log file</error>\n";
			return false;
		}
		
		return true;
	}

	/**
	 * keep log file
	 * append it to a more permanet one
	 * @return boolean true on sucess, false on error
	 */
	public function keepLog() {
		
		if( ! (Fishbones::getConfig()->debugLog ) ) { 
			return true;
		}

		$file = fopen( Fishbones::getConfig()->currentPathToStart .
			Fishbones::getConfig()->currentPathToFishbones .
			'logs/' .
			$this->keepfilename, 'at' 
		);
		
		$logCurrentSize = filesize(
				Fishbones::getConfig()->currentPathToStart .
				Fishbones::getConfig()->currentPathToFishbones .
				'logs/' .
				$this->keepfilename
			);
		
		if( $logCurrentSize > $this->keepMaxSize ) {
			ftruncate( $file, 0 );
		}
			

		
		$logtext = file_get_contents(
			Fishbones::getConfig()->currentPathToStart .
			Fishbones::getConfig()->currentPathToFishbones .
			'logs/' .
			$this->filename
		);
		
		fwrite( $file, "=====================================================================\n" );
		fwrite( $file, $logtext );
		fwrite( $file, "=====================================================================\n" );
		
		fclose( $file ); 
	}	
	
}

	
	
?>