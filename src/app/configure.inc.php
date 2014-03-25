<?php
// carcopy local xampp on URANUS
if ('localhost' == $_SERVER['SERVER_NAME'] || 'uranus' == $_SERVER['SERVER_NAME']){
	define('DB_SERVER', 'localhost');
	define('DB_NAME', 	'kassenbuch');
	define('DB_USER', 	'root');
	define('DB_PASS', 	'');
	define('DB_PORT', 	'3306');
}

// tanyll.de
if ('tanyll.de' == $_SERVER['SERVER_NAME']){
	error_reporting(NULL);

	define('DB_SERVER', 'localhost');
	define('DB_NAME', 	'tanyllde');
	define('DB_USER', 	'tanyllde');
	define('DB_PASS', 	'shdZGT530Kl;sh653#');
	define('DB_PORT', 	'3306');
}
?>