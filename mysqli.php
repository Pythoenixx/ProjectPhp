<?php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', ' ');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'projeck');

// Make the MySQL connection.
$dbc = @mysqli_connect(DB_HOST, DB_USER) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

// Select database.
@mysqli_select_db($dbc, DB_NAME) OR die ('Could not connect to MySQL database: ' . mysqli_error($dbc) );



?>

		