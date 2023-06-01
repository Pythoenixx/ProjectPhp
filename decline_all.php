<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// check kalu buton dah submit ?
if (isset($_POST['decline_all'])) {
    // Retrieve the order details
    $sql = "SELECT order_id, product_id, order_quantity FROM orders WHERE status='PENDING'";
    $result = $dbc->query($sql);

    // Loop through each order
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['order_id'];
        $productId = $row['product_id'];
        $orderQuantity = $row['order_quantity'];

        // Update order status to 'DECLINED'
        $updateOrderSql = "UPDATE orders SET status='DECLINED' WHERE order_id=$orderId";
        $dbc->query($updateOrderSql);

        // Add back the order quantity to the product
        $updateProductSql = "UPDATE products SET quantity = quantity + $orderQuantity WHERE product_id=$productId";
        $dbc->query($updateProductSql);
    }

    mysqli_close($dbc); // Close the database connection.

    // Redirect to the order_list page
    header('Location: order_list.php');
    exit();
}
?>
