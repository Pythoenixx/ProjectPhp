<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// ambil url dari order id
if (isset($_REQUEST['id'])) {
    $orderId = $_REQUEST['id'];

    // Retrieve the product ID and order quantity from the order
    $sql = "SELECT product_id, order_quantity FROM orders WHERE order_id = $orderId";
    $result = $dbc->query($sql);
    $row = $result->fetch_assoc();
    $productId = $row['product_id'];
    $orderQuantity = $row['order_quantity'];

    // Update the order status 
    $sql = "UPDATE orders SET status = 'DECLINED' WHERE order_id = $orderId";
    $dbc->query($sql);

    // Increase the product quantity in the database by the order quantity
    $sql = "UPDATE products SET quantity = quantity + $orderQuantity WHERE product_id = $productId";
    $dbc->query($sql);


    // Redirect back to the order management page
    header('Location: order_list.php');
    exit;
} else {
    // Invalid request, redirect to an error page or handle it accordingly
    header('Location: error.php');
    exit;
}
?>
