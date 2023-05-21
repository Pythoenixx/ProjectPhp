<?php
$page_title = 'View Suppliers';
include('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Registered Suppliers</h1>';

require_once('mysqli.php'); // Connect to the db.
global $dbc;

// Make the query.
$query = "SELECT supplier_id, supplier_name, contact_name, contact_email, contact_phone FROM suppliers ORDER BY supplier_id ASC";
$result = @mysqli_query($dbc, $query); // Run the query.
$num = @mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

if ($num > 0) { // If it ran OK, display the records.

    echo "<p>There are currently $num registered suppliers.</p>\n";

    // Table header.
    echo '<table align="center" cellspacing="0" cellpadding="5">
    <tr><td align="left"><b>Supplier ID</b></td><td align="left"><b>Supplier Name</b></td><td align="left"><b>Contact Name</b></td><td align="left"><b>Contact Email</b></td><td align="left"><b>Contact Phone</b></td></tr>';

    // Fetch and print all the records.
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>';
        echo '<td align="left">' . $row['supplier_id'] . '</td>';
        echo '<td align="left">' . $row['supplier_name'] . '</td>';
        echo '<td align="left">' . $row['contact_name'] . '</td>';
        echo '<td align="left">' . $row['contact_email'] . '</td>';
        echo '<td align="left">' . $row['contact_phone'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    @mysqli_free_result($result); // Free up the resources.
} else { // If it did not run OK.
    echo '<p class="error">There are currently no registered suppliers.</p>';
}

@mysqli_close($dbc); // Close the database connection.

include('./includes/footer.html'); // Include the HTML footer.
?>
