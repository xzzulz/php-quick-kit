<?php
// fishbones sample webservice:
// 
// simple database transaction

 
 
 
 
// point this to start.php
$pathToStart = '../../start.php';
include($pathToStart);	


/////////////////////////////////////////////////////////////////
// log debuging data

Fishbones::getLog()->writeDebug('var1: ' . $_POST['var1'] );
Fishbones::getLog()->writeDebug('var2: ' . $_POST['var2'] );


//////////////////////////////////////////////////////////////////
// config var

$development = ( Fishbones::getConfig()->mode == Config::MODE_DEVELOPMENT );

if( $development )
	Fishbones::getLog()->writeDebug('development: ' . $development );


///////////////////////////////////////////////////////////////////////

session_start();

// all post data is escaped for mysql
// see config.php
	
	
$itemId = $_POST['wi'];
$groupId = $_POST['si'];

$userId = $_SESSION['userId'];



///////////////////////////////////////////////////////////////////////////
// data validation


if( ! ctype_digit( $itemId ) )
	Fishbones::getPump()->outXmlErrorString("Invalid data");

if( ! ctype_digit( $groupId ) )
	Fishbones::getPump()->outXmlErrorString("Invalid data");

	
/////////////////////////////////////////////////////////////////////////////


Fishbones::getDB()->startTransaction();


/////////////////////////////////////////////////////////////////////////////
// db query


$sql = "
	SELECT 
	*
	FROM items_$groupId
	WHERE
	item_id = $itemId
";

$result = Fishbones::getDB()->queryAsArray( $sql );

if ( $result === false ) {
	Fishbones::getDB()->rollback();
	Fishbones::getPump()->outXmlErrorString("database error 80");
}


// check if item has been deleted
if( count( $result ) == 0 ) {
	Fishbones::getDB()->rollback();
	Fishbones::getPump()->outXmlValue("del");
}


$some_data		= $result['0']['some_data'];

Fishbones::getLog()->writeDebug( 'itemCreatorId: ' . $some_data );

$new_data = strrev( $some_data );




///////////////////////////////////////////////////////////////////////////
// update


$sql = "
	UPDATE items_$groupId SET

	some_data = '$$new_data'
	
	WHERE vote_user_id = '$userId'
	item_id = '$itemId'
";

$check = Fishbones::getDB()->query($sql);

if ($check === false) {
	Fishbones::getDB()->rollback();
	Fishbones::getPump()->outXmlErrorString("Error 92");
}
		

	
///////////////////////////////////////////////////////////////////////////


Fishbones::getDB()->commit();


///////////////////////////////////////////////////////////////////////////



// output

Fishbones::getPump()->outXmlValue( 'ok' );






