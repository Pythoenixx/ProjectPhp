<?php
$page_title = 'Approval';
include ('./includes/header.html');
?>
<!DOCTYPE html>
<html>
<head>
<title>Approve Order</title>
</head>
<body>
<h1>Approve Order</h1>

<form action="approve.php" method="post">
<input type="text" name="order_id">
<input type="submit" value="Approve" name="submit">
</form>

<?php
require_once('mysqli.php');
global $dbc;

// Check if the order ID is present in the POST request
if (isset($_REQUEST['submit'])) {
    // Get the order ID from the POST request
$order_id = $_POST['order_id'];

// Update the order status
$stmt = mysqli_prepare($dbc, "UPDATE orders SET status = 'Approved' WHERE order_id = ?");
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$affected_rows = mysqli_stmt_affected_rows($stmt);

// Check if the order exists and if it was successfully updated
if ($affected_rows == 0) {
    echo 'Order not found or already approved.';
    exit;
}

// Close the database connection
mysqli_close($dbc);

// Redirect the user to the order list page
header('Location: order_list.php');
exit;

}


?>

</body>
</html>
<?php
include ('./includes/footer.html');
?>