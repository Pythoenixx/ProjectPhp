<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// Update the status of all pending orders to "declined"
$sql = "UPDATE orders SET status='declined' WHERE status='pending'";
$dbc->query($sql);

mysqli_close($dbc); // Close the database connection.

// Redirect back to the order management page
header('Location: order_list.php');
exit();
?>
