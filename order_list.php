<?php
$page_title = 'Check Orders';
include('./includes/header.html');

// Connect to the database
require_once('mysqli.php');
global $dbc;

// Function to update the status of an order
function updateOrderStatus($orderId, $status)
{
    global $dbc;
    $sql = "UPDATE orders SET status='$status' WHERE order_id='$orderId'";
    $dbc->query($sql);
}

//search 
$searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

//  status filter
$statusFilter = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

// WHERE clause for the filters
$clause = '';
if (!empty($searchTerm)) {
    $clause .= "WHERE customer_name LIKE '%$searchTerm%' OR customer_address LIKE '%$searchTerm%' OR customer_phone LIKE '%$searchTerm%'";
}

if (!empty($statusFilter)) {
    if (!empty($clause)) {
        $clause .= ' AND ';
    } else {
        $clause .= 'WHERE ';
    }
    $clause .= "status = '$statusFilter'";
}

// Pagination code
$PageLimit = 5;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$startFrom = ($page - 1) * $PageLimit;

// Get the total number of orders 
$totalOrders= "SELECT COUNT(*) AS total FROM orders $clause";
$totalResult = $dbc->query($totalOrders);
$totalRow = $totalResult->fetch_assoc();
$totalOrder = $totalRow['total'];

// Calculate the number of pages
$total_pages = ceil($totalOrder / $PageLimit);

// Get the orders with the applied filters and pagination
$orderSql = "SELECT * FROM orders $clause LIMIT $startFrom, $PageLimit";
$orderResults = $dbc->query($orderSql);
mysqli_close($dbc); // Close the database connection.

?>
<html>
<head>
    <title>Order Management</title>
</head>
<body>
    <h1>Order Management</h1>

    <form method="post" action="order_list.php">
    <input type="text" name="search" placeholder="Search by name,address or phone number"size="35" maxlength="40" value="<?php echo isset($searchTerm) ? $searchTerm : ''; ?>">
        <select name="status">
            <option value="">All</option>
            <option value="pending" <?php if ($statusFilter == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="approved" <?php if ($statusFilter =='approved') echo 'selected'; ?>>Approved</option>
            <option value="declined" <?php if ($statusFilter == 'declined') echo 'selected'; ?>>Declined</option>
        </select>
        <input type="submit" value="Filter">
    </form>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Agent ID</th>
                <th>Customer Name</th>
                <th>Customer Address</th>
                <th>Customer Phone Number</th>
                <th>Product ID</th>
                <th>Order Quantity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderResults as $row) { ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['agent_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['customer_address']; ?></td>
                    <td><?php echo $row['customer_phone']; ?></td>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['order_quantity']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending') { ?>
                            <a href="approve.php?id=<?php echo $row['order_id']; ?>">Approve</a> |
                            <a href="decline.php?id=<?php echo $row['order_id']; ?>">Decline</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a href="?page=<?php echo $i . '&search=' . urlencode($searchTerm) . '&status=' . $statusFilter; ?>">Page <?php echo $i; ?></a>
    <?php } ?>
</div>


    <form method="POST" action="approve_all.php">
        <input type="submit" name="approve_all" value="Approve All">
    </form>

    <form method="POST" action="decline_all.php">
        <input type="submit" name="decline_all" value="Decline All">
    </form>
</body>
</html>

<?php
include('./includes/footer.html');
?>
