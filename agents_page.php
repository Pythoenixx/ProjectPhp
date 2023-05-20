<?php
$page_title = 'View Products';
include ('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Product List</h1>';

require_once('mysqli.php'); // Connect to the database.
global $dbc;

// Make the query.
$query = "SELECT * FROM products";
$result = mysqli_query($dbc, $query);

if ($result) { // If the query ran successfully, display the records.
    $num_rows = mysqli_num_rows($result);
    
    if ($num_rows > 0) {
        echo "<p>There are currently $num_rows products available.</p>\n";
        
        // Table header.
        echo '<table align="center" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="left"><b>Product Name</b></td>
                    <td align="left"><b>Product Description</b></td>
                </tr>';
        
        // Fetch and print all the records.
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td align="left">' . $row['product_name'] . '</td>
                    <td align="left">' . $row['product_description'] . '</td>
                  </tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p class="error">No products found.</p>';
    }
    
    mysqli_free_result($result); // Free up the resources.
} else {
    echo '<p class="error">Error retrieving products.</p>';
}

mysqli_close($dbc); // Close the database connection.

include ('./includes/footer.html'); // Include the HTML footer.
?>