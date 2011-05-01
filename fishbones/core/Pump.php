<?php
/**
 * fishbones microframework
 *
 * data output
 *
 */


/////////////////////////////////////////////////////////////////////////////////////



/**
 * The pump class outputs data to the client
 * 
 * @package fishbones.core
 */	
class Pump {
	
	/**
	 * send and xml DOMDocument to the php output, then die the script
	 * 
	 * @param $domXml DOMDocument xml will be converted to string, and send to output
	 * script dies
	 * 
	 */ 
	public function outXml(DOMDocument $domXml) {
		
		header("Content-Type: text/xml");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		
		$xmlString = $domXml->saveXML();
		Fishbones::getLog()->writeOutput( $xmlString );
		die( $xmlString );
		
	}
	
	/**
	 * output error data and die
	 * 
	 * @param $errorString String error info data, to put in the error xml document
	 * send script ouput and die the script
	 * 
	 */ 
	public function outXmlErrorString( $errorString ) {
		
		// note these headers
		// this will be revised in future versions
		header("Content-Type: text/xml");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		
		$domXml = new DOMDocument('1.0', 'utf-8');
		$dataNode = $domXml->appendChild(new DOMElement('data'));	
		
		//$errorNode = $dataNode->appendChild( new DOMElement('error', $errorString) );	
		$errorNode = $dataNode->appendChild( new DOMElement('error') );	
		$texNode = $domXml->createTextNode( $errorString );
		$errorNode->appendChild( $texNode );
		
		$xmlString = $domXml->saveXML();
		Fishbones::getLog()->writeOutput( $xmlString );
		die( $xmlString );
		
	}

	/**
	 * output xml doc <data><line><value>x</value></line></data>
	 * 
	 * @param $data String the data
	 * createa an xml document with a value node in a line node in a data root node
	 * send that to output, and die the script
	 * 
	 */ 
	public function outXmlValue( $value ) {
		
		header("Content-Type: text/xml");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		
		$domXml = new DOMDocument('1.0', 'utf-8');
		$dataNode = $domXml->appendChild(new DOMElement('data'));	
		$lineNode = $dataNode->appendChild( new DOMElement('line') );	
		
		//$valueNode = $lineNode->appendChild( new DOMElement('value', $value ) );	
		$valueNode = $lineNode->appendChild( new DOMElement('value') );	
		$texNode = $domXml->createTextNode( $value );
		$valueNode->appendChild( $texNode );
		
		$xmlString = $domXml->saveXML();
		Fishbones::getLog()->writeOutput( $xmlString );
		die( $xmlString );
		
	}	
}

?>