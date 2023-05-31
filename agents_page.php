<?php
if (true) {
    $page_title = 'View Products';
    include('./includes/header.html');
    require_once('mysqli.php'); // Connect to the database.
    global $dbc;
    session_start();
    $errors = [];
    // Check for supplier id.
    if (!isset($_SESSION['agent_id'])) {
        $errors[] = 'Invalid agent ID. Please log in again.';
    } else {
        $agent_id = $_SESSION['agent_id'];
        $agent_name = $_SESSION['agent_name'];
    }

    echo "<h1>🤵🏿$agent_name</h1>";

    // Make the query.
    $query = "SELECT product_name, product_description, price, quantity FROM products"; // Modify the query to include price and quantity
    $result = mysqli_query($dbc, $query);

    if ($result) { // If the query ran successfully, display the records.
        $num_rows = mysqli_num_rows($result);

        if ($num_rows > 0) {
            echo "<p>There are currently $num_rows products available.</p>\n";

            // Table header.
            echo '<table align="center" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="left"><b>Product Name</b></td>
                    <td align="left"><b>Product Description</b></td>
                    <td align="left"><b>Price</b></td> <!-- New column -->
                    <td align="left"><b>Quantity</b></td> <!-- New column -->
                </tr>';

            // Fetch and print all the records.
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                    <td align="left">' . $row['product_name'] . '</td>
                    <td align="left">' . $row['product_description'] . '</td>
                    <td align="left">' . $row['price'] . '</td> <!-- Display the price -->
                    <td align="left">' . $row['quantity'] . '</td> <!-- Display the quantity -->
                  </tr>';
            }

            echo '</table>';
        } else {
            echo '<p class="error">No products found.</p>';
        }

        mysqli_free_result($result); // Free up the resources.
    } else {
        echo '<p class="error">Error retrieving products.</p>';
    }
    if (isset($_POST['logout'])) {
        // Redirect to the same page
        header("Location: Logout.php");
        exit();
    }
    if (isset($_POST['order'])) {
        // Redirect to the same page
        header("Location: Order.php");
        exit();
    }
    if (isset($_POST['changePassword'])) {
        // Redirect to the same page
        header("Location: changePassword.php");
        exit();
    }

    mysqli_close($dbc); // Close the database connection.
?>

    <form action="agents_page.php" method="POST">
        <p><input type="submit" name="order" value="Make Order" /></p>
        <p><input type="submit" name="changePassword" value="Change Password" /></p>
        <p><input type="submit" name="logout" value="Logout" /></p>
    </form>

<?php
    include('./includes/footer.html'); // Include the HTML footer.
}
?>