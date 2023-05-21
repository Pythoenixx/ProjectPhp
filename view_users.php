<?php
$page_title = 'View the Current Users';
include('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Registered Users</h1>';

require_once('mysqli.php'); // Connect to the db.
global $dbc;

// Make the query.
$query = "SELECT user_id, username, password, first_name, last_name, email, phone_number, role FROM users ORDER BY user_id ASC";
$result = mysqli_query($dbc, $query); // Run the query.
$num = mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

if ($num > 0) { // If it ran OK, display the records.

    echo "<p>There are currently $num registered users.</p>\n";

    // Table header.
    echo '<table align="center" cellspacing="0" cellpadding="5">
    <tr>
        <td align="left"><b>Username</b></td>
        <td align="left"><b>Email</b></td>
        <td align="left"><b>Role</b></td>
    </tr>';

    // Fetch and print all the records.
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>';
        echo '<td align="left">' . $row['username'] . '</td>';
        echo '<td align="left">' . $row['email'] . '</td>';
        echo '<td align="left">' . $row['role'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    mysqli_free_result($result); // Free up the resources.
} else { // If it did not run OK.
    echo '<p class="error">There are currently no registered users.</p>';
}

mysqli_close($dbc); // Close the database connection.

include('./includes/footer.html'); // Include the HTML footer.
?>