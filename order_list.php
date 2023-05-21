<?php
$page_title = 'Check Orders';
include ('./includes/header.html');
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Management</title>
</head>
<body>
<h1>Order Management</h1>
<table>
<thead>
<tr>
<th>Order ID</th>
<th>Customer Name</th>
<th>Customer Address</th>
<th>Customer Phone Number</th>
<th>Product ID</th>
<th>Order Quantity</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php

// Connect to the database
require_once('mysqli.php');
    global $dbc;
// Get all the orders
$sql = 'SELECT * FROM orders';
$results = $dbc->query($sql);

// Loop through the results and display them in a table
foreach ($results as $row) {
    echo '<tr>';
    echo '<td>' . $row['order_id'] . '</td>';
    echo '<td>' . $row['customer_name'] . '</td>';
    echo '<td>' . $row['customer_address'] . '</td>';
    echo '<td>' . $row['customer_phone'] . '</td>';
    echo '<td>' . $row['product_id'] . '</td>';
    echo '<td>' . $row['order_quantity'] . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>';
    if ($row['status'] == 'pending') {
        echo '<a href="approve.php?id=' . $row['order_id'] . '">Approve</a>';
    } 
    echo '</td>';
    echo '</tr>';
}

mysqli_close($dbc); // Close the database connection.
?>
</tbody>
</table>
</body>
</html>

<?php
include ('./includes/footer.html');
?>