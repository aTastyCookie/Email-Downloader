<?php
set_time_limit(0); // make the script run for an infinite amount of time

/*-------- Database (MySQL) --------*/	

define("HOST"     ,	'localhost'); //database Host
define("DATABASE" ,	'mailbox'); //database name
define("USERNAME" ,	'root'  ); //database name
define("PASSWORD" ,	'abcd'); //database password

mysql_connect (HOST, USERNAME, PASSWORD)or die("Could not connect: ".mysql_error());
mysql_select_db(DATABASE) or die(mysql_error());
	
	
?>