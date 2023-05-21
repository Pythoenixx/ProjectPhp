<?php
$page_title = 'View Products';
include('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Product List</h1>';

require_once('mysqli.php'); // Connect to the db.
global $dbc;

// Make the query.
$query = "SELECT product_id, product_name, product_description, cost, price, quantity, supplier_id FROM products ORDER BY product_id ASC";
$result = @mysqli_query($dbc, $query); // Run the query.
$num = @mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

if ($num > 0) { // If it ran OK, display the records.

    echo "<p>There are currently $num products available.</p>\n";

    // Table header.
    echo '<table align="center" cellspacing="0" cellpadding="5">
    <tr><td align="left"><b>Product ID</b></td><td align="left"><b>Product Name</b></td><td align="left"><b>Description</b></td><td align="left"><b>Cost</b></td><td align="left"><b>Price</b></td><td align="left"><b>Quantity</b></td><td align="left"><b>Supplier ID</b></td></tr>';

    // Fetch and print all the records.
    while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>';
        echo '<td align="left">' . $row['product_id'] . '</td>';
        echo '<td align="left">' . $row['product_name'] . '</td>';
        echo '<td align="left">' . $row['product_description'] . '</td>';
        echo '<td align="left">' . $row['cost'] . '</td>';
        echo '<td align="left">' . $row['price'] . '</td>';
        echo '<td align="left">' . $row['quantity'] . '</td>';
        echo '<td align="left">' . $row['supplier_id'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    @mysqli_free_result($result); // Free up the resources.
} else { // If it did not run OK.
    echo '<p class="error">There are currently no products available.</p>';
}

@mysqli_close($dbc); // Close the database connection.

include('./includes/footer.html'); // Include the HTML footer.
?>
