<?php
// fishbones sample webservice:
// 
// simple load data as xml.
// The xml data can be used to feed a model in the client 
 
 
 
 
// point this to start.php
$pathToStart = '../../start.php';
include($pathToStart);	


///////////////////////////////////////////////////////////////////////////
// read data and send as xml


$sql = "
SELECT some_data
FROM some_table st
";


// get query result as xml dom
$xmlResul = Fishbones::getDB()->queryAsXmlDom( $sql );


// output
Fishbones::getPump()->outXml( $xmlResul );



