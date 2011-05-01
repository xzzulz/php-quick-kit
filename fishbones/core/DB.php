<?php
/**
 * fishbones microframework
 *
 * database class DB
 * 
 */


/////////////////////////////////////////////////////////////////////////////////////


/**
 * DB fishbones database class
 *
 * configure the database access parameters on Config class (  Config.php )
 * @package fishbones.core
 */
class DB {



	public $conex; // resource
	public $resul;
	public $error; // string

	// config
	public $xmloutOnError = true;


	private $transactionStarted = false;



	//////////////////////////////////////////////////////////////////////////////////
	// methods


	public function __construct() {

		
		$this->conex = mysql_connect( Fishbones::getConfig()->dbHost, Fishbones::getConfig()->dbUser, Fishbones::getConfig()->dbPass );

		if (!$this->conex) {
			$error = "database connection error 1";
			$this->error = $error;
		 	Fishbones::getLog()->writeDatabaseError($error);
			Fishbones::getPump()->outXml( $this->makeErrorXml($error) );							
			
		}

		$selected = mysql_select_db( Fishbones::getConfig()->dbDatabase, $this->conex );

		if (!$selected) {
			$error = "database selection error 2";
			$this->error = $error;
		 	Fishbones::getLog()->writeDatabaseError($error);
			Fishbones::getPump()->outXml( $this->makeErrorXml($error) );	
			
		}

	}


	
	
	/**
	 * execute a SQL query. dont return any resulset
	 *
	 * @param	string $sql	sql query
	 *.
	 * @return	boolean	trrue on sucess, false on error
	 */
	public function query( $sql ) {
		
		$this->error = '';

		Fishbones::getLog()->writeSql( $sql );
		$res = mysql_query( $sql, $this->conex );

		// query error
		if( !$res ) {
			$this->transactionStarted? $this->rollBack() : null ;
			$error = "database query error";
			$this->error = $error;
			Fishbones::getLog()->writeDatabaseError($error);
			Fishbones::getLog()->keepLog();
			return false;
		}

		// query sin resultados
		if ( $res == true ) {
			return true;
		}
		
	}
	
	
	/**
	 * execute a SQL query. return result resource
	 *
	 * @param	string $sql	sql query
	 *.
	 * @return	resource	
	 */	
	public function queryAsResource( $sql ) {
		
		$this->error = '';

		Fishbones::getLog()->writeSql( $sql );
		$res = mysql_query( $sql, $this->conex );
		
		if(!$res ) {
			Fishbones::getLog()->keepLog();
		}
		
		return $res;
		
	}


	/**
	 * executeas a sql and return result as array
	 *
	 * @param	string $sql	sql query
	 *
	 * @return	array|boolean	numeric indexed array of associative arrays with
	 * 					data value pairs, 
	 *					array with one element, empty array in case of sucess but no resulset
	 *					this cast to boolena true, and iterations do nothing
	 *					boolean false on error
	 * 
	 */
	public function queryAsArray( $sql ) {

		$this->error = '';

		Fishbones::getLog()->writeSql( $sql );
		$res = mysql_query( $sql, $this->conex );
			
		// query error
		if( !$res ) {
			$this->transactionStarted? $this->rollBack() : null ;
			$error = "database query error";
			$this->error = $error;
			Fishbones::getLog()->writeDatabaseError($error);
			Fishbones::getLog()->keepLog();
			return false;
		}

		// query sucess with reults
		if ( is_resource( $res ) ) {
				
			$this->resul = array();
			while ( $assoc = mysql_fetch_assoc( $res ) ) {
				$this->resul[] = $assoc;
			}
			return $this->resul;
		}
		
		
		// revised: just return an array
		
		// query sucess with no resulset
		// empty array cast to boolean false
		// so return array with one element. empty array, to make function result cast to true 
		//if ( $res == true ) {
		//if ( $res == true ) {
			$this->resul = array();
			//$this->resul[] = array();
			return $this->resul;
		//}

	}


	


	/**
	 * execute a sql and return an string with xml data
	 *
	 * @param	string $sql	sql query
	 *
	 * @return	DOMDocument	XML structured:
	 *					<data>
	 *					    <line>
	 *					        <value1>abc</value1>
	 *					        <value2>def</value2>
	 *					    </line>
	 *						...
	 *					    ...
	 *					</data>
	 * 				"data" (the root node)) and "line" nodes always use these names.
	 *				one "line" node is added for each returned row.
	 *				the value1, value2 are the cellnames as returned by the SQL query.
	 * 				boolean false on error
	 *				on query error, return a DOMDocument with fishbones standard
	 *				XML error document (data node, with one error node, containing the text description of the error).
	 *
	 */
	public function queryAsXmlDom( $sql ) {
		
		$result = $arrayResul = $this->queryAsArray( $sql );
	
		if ( is_array( $result) ) {	
			$xml = $this->arrayResultAsXml( $arrayResul );			
		} else {
			$xml = $this->makeErrorXml( $this->error );
			//$xml = $this->arrayResultAsXml( Array() );	
		}

		return $xml;
		
	}


	/**
	 * transaction operation
	 */
	public function startTransaction() {
		$this->query("SET autocommit = 0");
		$op = $this->query("START TRANSACTION");
		if ( $op ) $this->transactionStarted = true;
		return $op;
	}

	/**
	 * transaction operation
	 */
	public function commit() {
		$op = $this->query("COMMIT");
		if ( $op ) $this->transactionStarted = false;
		return $op;
	}
	
	/**
	 * transaction operation
	 */
	public function rollBack() {
		$op = $this->query("ROLLBACK");
		if ( $op ) $this->transactionStarted = false;
		return $op;
	}


	/**
	 * takes an array result from a query method of this class and return an DOMDocument with the data
	 *
	 * @param	array $arrayResul	array result 
	 *
	 * @return	DOMDocument		xml DOMDocument with result data
	 * 			
	 */
	public function arrayResultAsXml( array $arrayResul ) {

		$dom = new DOMDocument('1.0', 'utf-8');
		$dataNode = $dom->appendChild(new DOMElement('data'));

		if ( is_array( $arrayResul ) ) {
			
			foreach( $arrayResul as $arrayRecor ) {

				$lineNode = $dataNode->appendChild( new DOMElement('line') );

				foreach( $arrayRecor as $x => $y ) {
						
					//$cellNode = $lineNode->appendChild( new DOMElement( $x, $y ) );
					$cellNode = $lineNode->appendChild( new DOMElement( $x ) );
					
					$texNode = $dom->createTextNode( $y );
					$cellNode->appendChild( $texNode );
					

				}
				
			}

		}
			
		return $dom;
				

	}

	

	/**
	 * takes an string description from an error, and returnn a fishbones standard error DOMDocument
	 *
	 * @param	string $sql	sql query
	 *
	 * @return	integer mysql_insert_id
	 * 			
	 */
	public function lastInsertId() {

		$id = mysql_insert_id( $this->conex );
		return $id;
	}
	
	
	
	

	/**
	 * takes an string description from an error, and returnn a fishbones standard error DOMDocument
	 *
	 * @param	string $sql	sql query
	 *
	 * @return	DOMDocument|boolean		xml DOMDocument with result data
	 * 			data value pairs, boolean false on error
	 */
	public function makeErrorXml($errorString) {

		$dom = new DOMDocument('1.0', 'utf-8');
		$dataNode = $dom->appendChild(new DOMElement('data'));	
		$errorNode = $dataNode->appendChild( new DOMElement('error', $errorString) );
		
		$this->errorXML = $dom;
		return $dom;

	}
	
}
?>