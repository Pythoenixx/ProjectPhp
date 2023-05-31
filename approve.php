<?php
$page_title = 'Approval';
include('./includes/header.html');
?>

<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// Retrieve the order ID from the URL parameter
if (isset($_REQUEST['id'])) {
    $orderId = $_REQUEST['id'];

    // Update the order status in the database to 'approved'
    $sql = "UPDATE orders SET status = 'APPROVED' WHERE order_id = $orderId";
    $dbc->query($sql);

    // Perform any necessary actions for order approval

    // Redirect back to the order management page
    header('Location: order_list.php');
    exit;
} else {
    // Invalid request, redirect to an error page or handle it accordingly
    header('Location: error.php');
    exit;
}
include('./includes/footer.html');
?>
