<?php
$page_title = 'TotalPerformances';
include('./includes/header.html');
?>

<h1>Best Performances by Agents</h1>

<?php
require_once('mysqli.php');
global $dbc;

$query = "SELECT agents.agent_name, SUM(orders.order_quantity) AS total_quantity, SUM(orders.order_quantity * products.price) AS total_price, SUM(sales.profit) AS total_profit
          FROM agents
          INNER JOIN orders ON agents.agent_id = orders.agent_id
          INNER JOIN products ON orders.product_id = products.product_id
          INNER JOIN sales ON orders.order_id = sales.order_id
          GROUP BY agents.agent_id
          ORDER BY total_price DESC";

$result = $dbc->query($query);

if ($result->num_rows > 0) {
    echo '<table>
            <tr>
                <th>Agent Name</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Total Profit</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['agent_name'] . '</td>
                <td>' . $row['total_quantity'] . '</td>
                <td>' . $row['total_price'] . '</td>
                <td>' . $row['total_profit'] . '</td>
            </tr>';
    }

    echo '</table>';
} else {
    echo '<p>No data available.</p>';
}

mysqli_close($dbc);
?>

<?php include('./includes/footer.html'); ?>
