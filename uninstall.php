<?php

// TODO, is this needed...?
// is this global...? what if we include this files
// from a function...?
global $astman;

// remove all Call Forward options in effect on extensions
if ($astman) {
	$astman->database_deltree('CF');
	$astman->database_deltree('CFB');
	$astman->database_deltree('CFU');
} else {
	fatal("Cannot connect to Asterisk Manager with ".$amp_conf["AMPMGRUSER"]."/".$amp_conf["AMPMGRPASS"]);
}

?>
