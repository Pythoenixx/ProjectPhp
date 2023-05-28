<?php
// Include the database connection file
require_once('mysqli.php');
global $dbc;
// Check if the order ID is provided
if (isset($_REQUEST['order_id'])) {
    // Retrieve the order ID from the URL
    $orderId = $_REQUEST['order_id'];

    // Fetch the order details and associated products from the database using a single query
    $query = "SELECT o.order_id, o.order_date, o.customer_name, o.customer_address, o.customer_phone, o.status, op.order_quantity, p.product_name
              FROM orders o
              JOIN order_products op ON o.order_id = op.order_id
              JOIN products p ON op.product_id = p.product_id
              WHERE o.order_id = '$orderId'";
    $result = $dbc->query($query);

    // Check if the order exists
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Display the order details
        echo "<h1>Order Details</h1>";
        echo "<table>";
        echo "<tr><td>Order ID:</td><td>" . $order['order_id'] . "</td></tr>";
        echo "<tr><td>Order Date:</td><td>" . $order['order_date'] . "</td></tr>";
        echo "<tr><td>Customer Name:</td><td>" . $order['customer_name'] . "</td></tr>";
        echo "<tr><td>Customer Address:</td><td>" . $order['customer_address'] . "</td></tr>";
        echo "<tr><td>Customer Phone:</td><td>" . $order['customer_phone'] . "</td></tr>";
        echo "<tr><td>Status:</td><td>" . $order['status'] . "</td></tr>";
        echo "</table>";

        // Display the products associated with the order
        echo "<h2>Products:</h2>";
        echo "<table>";
        echo "<tr><th>Product Name</th><th>Quantity</th></tr>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['order_quantity'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Order not found.</p>";
    }
} else {
    // If the order ID is not provided or empty, display a form to input the order ID
    echo "<h1>Enter Order ID</h1>";
    echo "<form action='order_details.php' method='get'>";
    echo "<input type='text' name='order_id' placeholder='Order ID'>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>";

    // Validate the order ID
    if (isset($_REQUEST['order_id']) && !empty($_GET['order_id'])) {
        $orderId = $_REQUEST['order_id'];
        // Perform the validation or further processing here
    }
}

mysqli_close($dbc); // Close the database connection.
?>
