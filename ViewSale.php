<?php
$page_title = 'View Sales';
include('./includes/header.php');
?>

<h1>View Performance</h1>
<form action="ViewSale.php" method="post">
    <label for="role">View Performance by:</label>
    <select name="role" id="role" onchange="this.form.submit()">
        <option value="Sales">Sales</option>
        <option value="Total">Total</option>
        <?php
        $selected = (isset($_POST["role"])) ? $_POST["role"] : "Sales";
        if ($selected != "Sales") echo "<option value='$selected' selected style='display: none;'>$selected</option>";
        ?>
    </select>
    <div <?php if ($selected == "Total") echo "style='display: none;'"; ?>>
        <label for="filter">Filter by:</label>
        <select name="filter" id="filter">
            <option value="Agents" <?php if (isset($_POST['filter']) && $_POST['filter'] == "Agents") echo "selected"; ?>>Agents</option>
            <option value="Products" <?php if (isset($_POST['filter']) && $_POST['filter'] == "Products") echo "selected"; ?>>Products</option>
            <option value="AgentsAndProducts" <?php if (isset($_POST['filter']) && $_POST['filter'] == "AgentsAndProducts") echo "selected"; ?>>Agents and Products</option>
        </select>
        <button type="submit">View</button>
    </div>

</form>

<?php

if (!isset($_SESSION['supplier_id'])) {
    $errors[] = 'Invalid supplier ID. Please log in again.';
} else {
    $supplier_id = $_SESSION['supplier_id'];
}
// Include the database connection file
if (isset($selected)) {
    require_once('mysqli.php');
    global $dbc;

    $s = mysqli_query($dbc, "SELECT orders.order_id, orders.order_date, orders.order_quantity, orders.product_id, orders.agent_id, products.cost, products.price from orders 
        INNER JOIN products on orders.product_id = products.product_id 
        WHERE status='approved'");
        $num = @mysqli_num_rows($s);

        if ($num > 0) {
            while ($row = mysqli_fetch_array($s)) {

                $order_id = $row['order_id'];
                $product_id = $row['product_id'];
                $agent_id = $row['agent_id'];
                $salesDate = $row['order_date'];
                $quantitySold = $row['order_quantity'];
                $productCost = $row['cost'];
                $productPrice = $row['price'];
                $totalSell = $quantitySold * $productPrice;
                $commission = 0.05 * ($totalSell);
                $netAmount = $totalSell - $commission;
                $profit = $netAmount - $productCost;


                $sql = "INSERT INTO sales (sales_date, quantity_sold, commission, profit, total_sell, net_amount, order_id, product_id, agent_id) VALUES ('$salesDate', '$quantitySold', '$commission', '$profit', '$totalSell', '$netAmount', '$order_id', '$product_id', '$agent_id')";
                $stmt = $dbc->prepare($sql);

                if ($stmt->execute()) {
                    $updateQuery = "UPDATE orders
                    SET status = 'DELIVERED'
                    WHERE LOWER(status) = 'approved';";
                    $dbc->query($updateQuery);
                } else {
                    echo 'Error: Unable to insert data into the sales table.';
                }
            }
        }
    $view_by = $selected;
    if ($view_by == "Total") {

         
        // Retrieve the sales data from the database
        $query = "SELECT sales.sales_id, sales.sales_date, sales.quantity_sold, sales.commission, sales.profit, sales.total_sell, sales.net_amount, orders.order_id, products.product_id, agents.agent_id, agents.supplier_id
          FROM sales
          INNER JOIN orders ON sales.order_id = orders.order_id
          INNER JOIN products ON sales.product_id = products.product_id
          INNER JOIN agents ON sales.agent_id = agents.agent_id 
          WHERE agents.supplier_id = $supplier_id";
        $result = $dbc->query($query);

        // Check if there are any sales records
        if ($result->num_rows > 0) {
            // Display the sales data in a table
    echo '<table align="center">
            <tr>
                <th>Sale ID</th>
                <th>Quantity Sold</th>
                <th>Total Sell</th>
                <th>Net Amount</th>
                <th>Commission</th>
                <th>Profit</th>
                <th>Sale Date</th>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Agent ID</th>
            </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                <td>' . $row['sales_id'] . '</td>
                <td>' . $row['quantity_sold'] . '</td>
                <td>' . $row['total_sell'] . '</td>
                <td>' . $row['net_amount'] . '</td>
                <td>' . $row['commission'] . '</td>
                <td>' . $row['profit'] . '</td>
                <td>' . $row['sales_date'] . '</td>
                <td>' . $row['order_id'] . '</td>
                <td>' . $row['product_id'] . '</td>
                <td>' . $row['agent_id'] . '</td>
            </tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No sales records found.</p>';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
            require_once('mysqli.php');
            global $dbc;

            $filter = $_POST['filter'];

            if ($filter == "Agents") {
                $query = "SELECT agents.agent_name, GROUP_CONCAT(DISTINCT products.product_name SEPARATOR ',') AS product_name,
            SUM(orders.order_quantity) AS total_quantity
     FROM agents
     INNER JOIN orders ON agents.agent_id = orders.agent_id
     INNER JOIN products ON orders.product_id = products.product_id
     WHERE agents.supplier_id = $supplier_id AND orders.status = 'DELIVERED'
     GROUP BY agents.agent_name
     ORDER BY total_quantity DESC";
                $title = "Agent with Highest Sale";
                $agentColumn = true;
            } elseif ($filter == "Products") {
                $query = "SELECT products.product_name, SUM(orders.order_quantity) AS total_quantity
                      FROM products
                      INNER JOIN orders ON products.product_id = orders.product_id
                      WHERE products.supplier_id = $supplier_id AND orders.status = 'DELIVERED'
                      GROUP BY products.product_name
                      ORDER BY total_quantity DESC";
                $title = "Products with Highest Sold";
                $agentColumn = false;
            } elseif ($filter == "AgentsAndProducts") {
                $query = "SELECT agent_name, product_name, order_quantity AS total_quantity
                FROM (
                  SELECT agents.agent_name, products.product_name, orders.order_quantity,
                  MAX(orders.order_quantity) OVER (PARTITION BY agents.agent_id) AS max_quantity
                  FROM agents
                  INNER JOIN orders ON agents.agent_id = orders.agent_id
                  INNER JOIN products ON orders.product_id = products.product_id
                  WHERE agents.supplier_id = $supplier_id AND orders.status = 'DELIVERED'
                ) AS t
                WHERE order_quantity = max_quantity
                ORDER BY total_quantity DESC";
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
        }
    }

    // Close the database connection
    mysqli_close($dbc);
}
?>

<?php include('./includes/footer.html'); ?>