<?php
$page_title = 'View Sales';
include('./includes/header.html');
?>

<h1>View Sales</h1>

<?php
// Include the database connection file
require_once('mysqli.php');
global $dbc;

// Retrieve the sales data from the database
$query = "SELECT sales.sales_id, sales.sales_date, sales.quantity_sold, sales.commission, sales.profit, sales.discount, sales.total_sell, sales.net_amount, orders.order_id, products.product_id, agents.agent_id
          FROM sales
          INNER JOIN orders ON sales.order_id = orders.order_id
          INNER JOIN products ON sales.product_id = products.product_id
          INNER JOIN agents ON sales.agent_id = agents.agent_id";
$result = $dbc->query($query);

// Check if there are any sales records
if ($result->num_rows > 0) {
    // Display the sales data in a table
    echo '<table>
            <tr>
                <th>Sale ID</th>
                <th>Sale Date</th>
                <th>Quantity Sold</th>
                <th>Commission</th>
                <th>Profit</th>
                <th>Discount</th>
                <th>Total Sell</th>
                <th>Net Amount</th>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Agent ID</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['sales_id'] . '</td>
                <td>' . $row['sales_date'] . '</td>
                <td>' . $row['quantity_sold'] . '</td>
                <td>' . $row['commission'] . '</td>
                <td>' . $row['profit'] . '</td>
                <td>' . $row['discount'] . '</td>
                <td>' . $row['total_sell'] . '</td>
                <td>' . $row['net_amount'] . '</td>
                <td>' . $row['order_id'] . '</td>
                <td>' . $row['product_id'] . '</td>
                <td>' . $row['agent_id'] . '</td>
            </tr>';
    }

    echo '</table>';
} else {
    echo '<p>No sales records found.</p>';
}

// Close the database connection
mysqli_close($dbc);
?>

<?php include('./includes/footer.html'); ?>
