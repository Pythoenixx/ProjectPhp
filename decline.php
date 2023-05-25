<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// Retrieve the order ID from the URL parameter
if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Update the order status in the database to 'declined'
    $sql = "UPDATE orders SET status = 'declined' WHERE order_id = $orderId";
    $dbc->query($sql);

    // Perform any necessary actions for order decline

    // Redirect back to the order management page
    header('Location: order_list.php');
    exit;
} else {
    // Invalid request, redirect to an error page or handle it accordingly
    header('Location: error.php');
    exit;
}
?>
