
<?php # Script 11.6 - mysqli.php
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', ' ');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'projeck');
// Make the connection.
$dbc = @mysqli_connect(DB_HOST, DB_USER) OR die ('Could not connect to MySQL: ' . mysqli_connect_errno() );
echo"<p>Successfully connected to MySQL</p>\n";
@mysqli_select_db($dbc , DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_errno() );
// Make the query.
$query = "SELECT CONCAT(last_name, ', ', first_name) AS name, DATE_FORMAT(registration_date, '%M %d, %Y') 
		AS dr FROM users ORDER BY registration_date ASC";

// Run the query and handle the results.
if ($result = @mysqli_query($dbc, $query)) {

	if (mysqli_num_rows($result) > 0) { // Some records returned.
	
		// Print each record in a loop.
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			echo "<h3>$row[0]</h3>
			<p>$row[1]</p><br />";
		}
	
	} else { // No records returned.
		echo '<p>There are currently no comments in the database.</p>';
	}

} else { // Query didn't run properly.
	echo '<p><font color="red">MySQL Error: ' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</font></p>';// Debugging message.
}

// Free the result and close the connection.
mysqli_free_result($result);
mysqli_close($dbc);
?>
