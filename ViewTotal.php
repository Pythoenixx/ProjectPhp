<?php
$page_title = 'View Total Performance';
include('./includes/header.php');
?>

<h1>View Data</h1>

<form action="ViewTotal.php" method="post">
    <label for="filter">Filter by:</label>
    <select name="filter" id="filter">
        <option value="Agents" <?php if(isset($_POST['filter']) && $_POST['filter'] == "Agents") echo "selected"; ?>>Agents</option>
        <option value="Products" <?php if(isset($_POST['filter']) && $_POST['filter'] == "Products") echo "selected"; ?>>Products</option>
        <option value="AgentsAndProducts" <?php if(isset($_POST['filter']) && $_POST['filter'] == "AgentsAndProducts") echo "selected"; ?>>Agents and Products</option>
    </select>
    <button type="submit">View</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
    require_once('mysqli.php');
    global $dbc;

    $filter = $_POST['filter'];

    if ($filter == "Agents") {
        $query = "SELECT agents.agent_name, products.product_name, SUM(orders.order_quantity) AS total_quantity
                  FROM agents
                  INNER JOIN orders ON agents.agent_id = orders.agent_id
                  INNER JOIN products ON orders.product_id = products.product_id
                  GROUP BY agents.agent_name
                  ORDER BY total_quantity DESC LIMIT 1";
        $title = "Agent with Highest Sale";
        $agentColumn = true;
    } elseif ($filter == "Products") {
        $query = "SELECT products.product_name, SUM(orders.order_quantity) AS total_quantity
                  FROM products
                  INNER JOIN orders ON products.product_id = orders.product_id
                  GROUP BY products.product_name
                  ORDER BY total_quantity DESC";
        $title = "Products with Highest Sold";
        $agentColumn = false;
    } elseif ($filter == "AgentsAndProducts") {
        $query = "SELECT agents.agent_name, products.product_name, SUM(orders.order_quantity) AS total_quantity
                  FROM agents
                  INNER JOIN orders ON agents.agent_id = orders.agent_id
                  INNER JOIN products ON orders.product_id = products.product_id
                  GROUP BY agents.agent_name, products.product_name
                  ORDER BY total_quantity DESC LIMIT 1";
        $title = "Agent and Product with Highest Sale";
        $agentColumn = true;
    }

    $result = $dbc->query($query);

    if ($result->num_rows > 0) {
        echo '<h2>' . $title . '</h2>';
        echo '<table>
                <tr>';
        if ($agentColumn) {
            echo '<th>Agent Name</th>';
        }
        echo '<th>Product Name</th>
                    <th>Total Quantity</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            if ($agentColumn) {
                echo '<td>' . $row['agent_name'] . '</td>';
            }
            echo '<td>' . $row['product_name'] . '</td>
                  <td>' . $row['total_quantity'] . '</td>
                </tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No data available.</p>';
    }

    mysqli_close($dbc);
}

include('./includes/footer.html');
?>
