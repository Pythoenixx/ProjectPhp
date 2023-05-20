<?php

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', ' ');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'projeck');

// Make the MySQL connection.
$dbc = @mysqli_connect(DB_HOST, DB_USER) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
echo"<p>Successfully connected to MySQL</p>\n";

// Select database.
@mysqli_select_db($dbc, DB_NAME) OR die ('Could not connect to MySQL database: ' . mysqli_error($dbc) );
echo"<p>Database name = ".DB_NAME."</p>\n";


?>

		