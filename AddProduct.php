<?php
$page_title = 'Add Product';
include('./includes/header.html');

require_once('mysqli.php'); // Connect to the db.
global $dbc;

$query0 = "SELECT product_name, product_description, price, cost FROM products";
$result = mysqli_query($dbc, $query0); // Run the query.
$num = mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

if ($num > 0) { // If it ran OK, display the records.

    echo "<p>There are currently $num products.</p>\n";

    // Table header.
    echo '<table align="center" cellspacing="0" cellpadding="5">
        <tr>
            <td align="left"><b>Product Name</b></td>
            <td align="left"><b>Product Description</b></td>
            <td align="left"><b>Price</b></td>
            <td align="left"><b>Cost</b></td>
        </tr>';

    // Fetch and print all the records.
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
            <td align="left">' . $row['product_name'] . '</td>
            <td align="left">' . $row['product_description'] . '</td>
            <td align="left">' . $row['price'] . '</td>
            <td align="left">' . $row['cost'] . '</td>
        </tr>';
    }

    echo '</table>';

    mysqli_free_result($result); // Free up the resources.

} else { // If it did not run OK.
    echo '<p class="error">There are currently no products.</p>';
}

// Check if the form has been submitted
if (isset($_POST['submitted'])) {
    $errors = array(); // Initialize error array.

    // Check for product name.
    if (empty($_POST['product_name'])) {
        $errors[] = 'You forgot to enter the product name.';
    } else {
        $product_name = $_POST['product_name'];

        // Check if product name already exists.
        $query = "SELECT product_name FROM products WHERE product_name = '$product_name'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = 'Product name already exists.';
        }
    }

    // Check for product description.
    if (empty($_POST['product_description'])) {
        $errors[] = 'You forgot to enter the product description.';
    } else {
        $product_description = $_POST['product_description'];
    }

    // Check for cost.
    if (empty($_POST['cost'])) {
        $errors[] = 'You forgot to enter the cost.';
    } else {
        $cost = $_POST['cost'];
    }

    // Check for price.
    if (empty($_POST['price'])) {
        $errors[] = 'You forgot to enter the price.';
    } else {
        $price = $_POST['price'];
    }

    // Check for quantity.
    if (empty($_POST['quantity'])) {
        $errors[] = 'You forgot to enter the quantity.';
    } else {
        $quantity = $_POST['quantity'];
    }

    // Check for supplier ID.
    if (empty($_POST['supplier_id'])) {
        $errors[] = 'You forgot to enter the supplier ID.';
    } else {
        $supplier_id = $_POST['supplier_id'];

        // Validate supplier ID format.
        if (!preg_match('/^8\d{3}$/', $supplier_id)) {
            $errors[] = 'Supplier ID must start with 8 and have 4 digits.';
        } else {
            // Check if supplier ID already exists.
            $query = "SELECT supplier_id, COUNT(*) AS product_count FROM products WHERE supplier_id = '$supplier_id'";
            $result = mysqli_query($dbc, $query);
            $row = mysqli_fetch_assoc($result);
            $productCount = $row['product_count'];

            if ($productCount >= 3) {
                $errors[] = 'Supplier ID already has 3 or more products. Please choose a different supplier ID.';
            }
        }
    }

    if (empty($errors)) { // If everything's okay.

        // Insert the product into the database.
        $query = "INSERT INTO products (product_name, product_description, cost, price, quantity, supplier_id) VALUES ('$product_name', '$product_description', '$cost', '$price', '$quantity', '$supplier_id')";
        $result = mysqli_query($dbc, $query);

        if ($result) { // If it ran OK.

            // Print a success message.
            echo '<h1 id="mainhead">Success!</h1>';
            echo '<p>The product has been added successfully.</p>';

            // Include the footer and quit the script (to not show the form).
            include('./includes/footer.html');
            exit();

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>';
            echo '<p class="error">The product could not be added due to a system error. We apologize for any inconvenience.</p>';
            echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>';
            include('./includes/footer.html');
            exit();
        }
    } else { // Report the errors.

        echo '<h1 id="mainhead">Error!</h1>';
        echo '<p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    } // End of if (empty($errors)) IF.

    mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.

if (isset($_POST['logout'])) {
    // Redirect to the same page
    header("Location: Logout.php");
    exit();
}
?>

<h2>Add Product</h2>
<form action="AddProduct.php" method="post">
    <p>Product.Name.: <input type="text" name="product_name" size="30" maxlength="50" value="<?php if (isset($_POST['product_name'])) echo $_POST['product_name']; ?>" /></p>
    <p>Product Description:</p>
    <p><textarea name="product_description" rows="5" cols="30"><?php if (isset($_POST['product_description'])) echo $_POST['product_description']; ?></textarea></p>
    <p>Cost: <input type="text" name="cost" size="10" maxlength="10" value="<?php if (isset($_POST['cost'])) echo $_POST['cost']; ?>" /></p>
    <p>Price: <input type="text" name="price" size="10" maxlength="10" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>" /></p>
    <p>Quantity: <input type="text" name="quantity" size="5" maxlength="5" value="<?php if (isset($_POST['quantity'])) echo $_POST['quantity']; ?>" /></p>
    <p>Supplier ID: <input type="text" name="supplier_id" size="5" maxlength="5" value="<?php if (isset($_POST['supplier_id'])) echo $_POST['supplier_id']; ?>" /></p>
    <p><input type="submit" name="submit" value="Add Product" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
<form action="AddProduct.php" method="post">
    <input type="submit" name="logout" value="Logout" />
</form>

<?php include('./includes/footer.html'); ?>
