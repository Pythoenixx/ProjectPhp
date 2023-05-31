<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// ambik customer name yang dipilih dalam form 
$selectCustomer = isset($_POST['decline_select']) ? $_POST['decline_select'] : '';

// Update the status untuk selected customer
$sql = "UPDATE orders SET status='DECLINE' WHERE customer_name='$selectCustomer' AND status='pending'";
$dbc->query($sql);

mysqli_close($dbc); // Close the database connection.

// Redirect to the order management page
header('Location: order_list.php');
exit();
?>
