<?php
$page_title = 'Check Orders';
include('./includes/header.php');

// Connect to the database
require_once('mysqli.php');
global $dbc;

$errors = [];

if (!isset($_SESSION['role'])) {
    $errors[] = 'Invalid role. Please log in again.';
} 

// If there are any errors, display them and exit
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    exit();
}

if ($role == 'supplier') {
    // Function to update the status of an order
    function updateOrderStatus($orderId, $status)
    {
        global $dbc;
        $supplierAgentId = $_SESSION['agent_id'];
        $sql = "UPDATE orders SET status='$status' WHERE order_id='$orderId' AND agent_id='$supplierAgentId'";
        $dbc->query($sql);
    }

    // Search
    $cariInfo = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';

    // Status filter
    $statusFilter = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

    // WHERE clause for the filters
    $clause = '';
    if (!empty($cariInfo)) {
        $clause .= "AND (customer_name LIKE '%$cariInfo%' OR customer_address LIKE '%$cariInfo%' OR customer_phone LIKE '%$cariInfo%')";
    }

    if (!empty($statusFilter)) {
        $clause .= "AND status = '$statusFilter'";
    }

    // Pagination code
    $PageLimit = 5;
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $startFrom = ($page - 1) * $PageLimit;

    // Get the total number of orders
    $totalOrders = "SELECT COUNT(*) AS total FROM orders 
                    WHERE agent_id IN (
                      SELECT agent_id FROM agents WHERE supplier_id = '{$_SESSION['supplier_id']}'
                    ) $clause";
    $totalResult = $dbc->query($totalOrders);
    $totalRow = $totalResult->fetch_assoc();
    $totalOrder = $totalRow['total'];

    // Calculate the number of pages
    $total_pages = ceil($totalOrder / $PageLimit);

    // Retrieve orders and unique customer names
    $orderSql = "SELECT * FROM orders 
                  WHERE agent_id IN (
                    SELECT agent_id FROM agents WHERE supplier_id = '{$_SESSION['supplier_id']}'
                  ) $clause
                  LIMIT $startFrom, $PageLimit";
    $orderResults = $dbc->query($orderSql);

    $customerNames = array();
    foreach ($orderResults as $row) {
        $customerNames[$row['customer_name']] = $row['customer_name'];
    }

    mysqli_close($dbc); // Close the database connection.
?>

<html>

<head>
    <title>Order Management</title>
</head>

<body>
    <h1>Order Management</h1>

    <form method="post">
        <input type="text" name="search" placeholder="Search by name, address, or phone number" size="35" maxlength="40" value="<?php echo isset($cariInfo) ? $cariInfo : ''; ?>">
        <select name="status">
            <option value="">All</option>
            <option value="PENDING" <?php if ($statusFilter == 'PENDING') echo 'selected'; ?>>Pending</option>
            <option value="APPROVE" <?php if ($statusFilter == 'APPROVE') echo 'selected'; ?>>Approved</option>
            <option value="DECLINED" <?php if ($statusFilter == 'DECLINED') echo 'selected'; ?>>Declined</option>
        </select>
        <input type="submit" value="Search">
    </form>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Agent ID</th>
            <th>Customer Name</th>
            <th>Customer Address</th>
            <th>Customer Phone</th>
            <th>Product ID</th>
                <th>Order Quantity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        if ($totalOrder > 0) {
            foreach ($orderResults as $row) {
        ?>
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
                        <form method="post" action="">
                        <?php if ($row['status'] == 'PENDING') { ?>
                                <a href="approve.php?id=<?php echo $row['order_id']; ?>">Approve</a> |
                                <a href="decline.php?id=<?php echo $row['order_id']; ?>">Declined</a>
                            <?php } ?>
                        </form>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='9'>No orders found.</td></tr>";
        }
        ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?php echo $i . '&search=' . urlencode($cariInfo) . '&status=' . $statusFilter; ?>">Page <?php echo $i; ?></a>
        <?php } ?>
    </div>

    <form method="POST" action="approve_all.php">
        <?php if ($statusFilter !== 'approved' && $statusFilter !== 'declined') { ?>
            <label for="approve_select">Approve All:</label>
            <select name="approve_select" id="approve_select">
                <option value="">All</option>
                <?php foreach ($customerNames as $customerName) { ?>
                    <option value="<?php echo $customerName; ?>"><?php echo $customerName; ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="approve_all" value="Approve">
        <?php } ?>
    </form>

    <form method="POST" action="decline_all.php">
        <?php if ($statusFilter !== 'approved' && $statusFilter !== 'declined') { ?>
            <label for="decline_select">Decline All:</label>
            <select name="decline_select" id="decline_select">
                <option value="">All</option>
                <?php foreach ($customerNames as $customerName) { ?>
                    <option value="<?php echo $customerName; ?>"><?php echo $customerName; ?></option>
                <?php } ?>
            </select>
            <input type="submit" name="decline_all" value="Decline">
        <?php } ?>
    </form>
</body>

</body>

</html>
<?php
  include('./includes/footer.html');
} else {
    header("Location: Order.php");
    exit();
}
?>