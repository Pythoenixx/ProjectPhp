<?php
$page_title = 'View the Current Users';
include('./includes/header.html');
?>
<h1 id="mainhead">Users</h1>
<form action="view_users.php" method="get">
    <label for="role">filter by role:</label>
    <select name="role" id="role" onchange="this.form.submit()">
        <option value="All">All</option>
        <option value="Agent">Agent</option>
        <option value="Supplier">Supplier</option>
        <?php
        // Get the selected value from the query string
        $selected = (isset($_GET["role"])) ? $_GET["role"] : "All";
        // if the value isn't All, create new option, make it selected and hide it
        if ($selected != "All") echo "<option value='$selected' selected style='display: none;'>$selected</option>";
        ?>
    </select>
</form>


<?php

require_once('mysqli.php'); // Connect to the db.
global $dbc;

$table = $selected;

if ($table == "All") {
    // Make the query.
    $query = "SELECT user_id, username, password, email, phone_number, role FROM users ORDER BY user_id ASC";
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
} elseif ($table == "Agent") {
    // Make the query.
    $query = "SELECT agent_id, agent_name, contact_name, contact_email, contact_phone, supplier_id FROM agents ORDER BY agent_id ASC";
    $result = @mysqli_query($dbc, $query); // Run the query.
    $num = @mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

    if ($num > 0) { // If it ran OK, display the records.

        echo "<p>There are currently $num registered agents.</p>\n";

        // Table header.
        echo '<table align="center" cellspacing="0" cellpadding="5">
<tr><td align="left"><b>Agent ID</b></td><td align="left"><b>Agent Name</b></td><td align="left"><b>Username</b></td><td align="left"><b>Contact Email</b></td><td align="left"><b>Contact Phone</b></td><td align="left"><b>Supplier ID</b></td></tr>';

        // Fetch and print all the records.
        while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<tr>';
            echo '<td align="left">' . $row['agent_id'] . '</td>';
            echo '<td align="left">' . $row['agent_name'] . '</td>';
            echo '<td align="left">' . $row['contact_name'] . '</td>';
            echo '<td align="left">' . $row['contact_email'] . '</td>';
            echo '<td align="left">' . $row['contact_phone'] . '</td>';
            echo '<td align="left">' . $row['supplier_id'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        @mysqli_free_result($result); // Free up the resources.
    } else { // If it did not run OK.
        echo '<p class="error">There are currently no registered agents.</p>';
    }
} else {
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
}


mysqli_close($dbc); // Close the database connection.

include('./includes/footer.html'); // Include the HTML footer.
?>