<?php
$page_title = 'Approval';
include('./includes/header.html');
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

        // Retrieve the order details and associated product price
        $query = "SELECT o.order_quantity, p.price, o.product_id, o.agent_id FROM orders AS o
                  INNER JOIN products AS p ON o.product_id = p.product_id
                  WHERE o.order_id = '$order_id'";
        $result = $dbc->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $quantity_sold = $row['order_quantity'];
            $price = $row['price'];
            $product_id = $row['product_id'];
            $agent_id = $row['agent_id'];

            // Retrieve the cost from the product table
            $product_query = "SELECT cost FROM products WHERE product_id = '$product_id'";
            $product_result = $dbc->query($product_query);

            if ($product_result->num_rows > 0) {
                $product_row = $product_result->fetch_assoc();
                $cost = $product_row['cost'];

                // Calculate commission, profit, discount, total_sell, and net_ammount
                $commission = 0.05 * ($quantity_sold * $price);
                $profit = ($quantity_sold * $price) - ($quantity_sold * $cost);
                $discount = ($quantity_sold * $price) >= 1000 ? 0.1 * ($quantity_sold * $price) : 0;
                $total_sell = $quantity_sold * $price;
                $net_ammount = $total_sell - $discount;

                // Insert the approved order data into the sales table
                $insert_query = "INSERT INTO sales (sales_date, quantity_sold, commission, profit, discount, total_sell, net_amount, order_id, product_id, agent_id)
                                 VALUES (CURDATE(), '$quantity_sold', '$commission', '$profit', '$discount', '$total_sell', '$net_ammount', '$order_id', '$product_id', '$agent_id')";
                $insert_result = $dbc->query($insert_query);

                if ($insert_result) {
                    echo 'Order approved and data inserted into the sales table.';
                } else {
                    echo 'Error: Unable to insert data into the sales table.';
                }
            } else {
                echo 'Error: Product cost not found.';
            }
        } else {
            echo 'Error: Order details not found.';
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
include('./includes/footer.html');
?>
