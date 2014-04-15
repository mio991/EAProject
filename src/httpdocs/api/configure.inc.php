<?php

// local xampp (default)
if ('localhost' == $_SERVER['SERVER_NAME']){
	define('DB_SERVER', 'localhost');
	define('DB_NAME', 	'eaproject');
	define('DB_USER', 	'root');
	define('DB_PASS', 	'');
	define('DB_PORT', 	'3306');
}

// tanyll.de
if ('urlaub.tanyll.de' == $_SERVER['SERVER_NAME']){
	error_reporting(NULL);

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 	'EA_PROJECT');
	define('DB_USER', 	'r00tusr');
	define('DB_PASS', 	'd7GR42$h(/;sdad;#');
	define('DB_PORT', 	'3306');
}

// additional servers
if ('mein_server.de' == $_SERVER['SERVER_NAME']){
	error_reporting(NULL);

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 	'eaproject');
	define('DB_USER', 	'');
	define('DB_PASS', 	'');
	define('DB_PORT', 	'3306');
}
?>