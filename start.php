<?php
// fishbones starting point


// this var is required
// put Fish folder in a non internet accesable folder for security , and modify the path var below accordingly
$pathToFishbones = 'fishbones/';


// config should come first
include($pathToFishbones.'Config.php');

// second the core classes
include($pathToFishbones.'core/Log.php');
include($pathToFishbones.'core/DB.php');
include($pathToFishbones.'core/Pump.php');
include($pathToFishbones.'core/CleanVars.php');

// the Fish class
include($pathToFishbones.'core/Fishbones.php');



/////////////////////////////////////////////////////////////////////////////////

Fishbones::getConfig()->currentPathToStart = $pathToStart;
Fishbones::getConfig()->currentPathToFishbones = $pathToFishbones;

/////////////////////////////////////////////////////////////////////////////////

if ( Fishbones::getConfig()->autoEscapeHttpRequestVars ) {
	Fishbones::getCleanVars()->cleanHttpRequestVars();
}

/////////////////////////////////////////////////////////////////////////////////
		
	
?>