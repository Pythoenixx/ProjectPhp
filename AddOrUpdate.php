<?php
$page_title = 'Add/Update Product';
include('./includes/header.html');

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    require_once('mysqli.php'); // Connect to the database

    $errors = array(); // Initialize error array

    // Check for product name
    if (empty($_POST['product_name'])) {
        $errors[] = 'You forgot to enter the product name.';
    } else {
        $product_name = $_POST['product_name'];
    }

    // Check for product description
    if (empty($_POST['product_description'])) {
        $errors[] = 'You forgot to enter the product description.';
    } else {
        $product_description = $_POST['product_description'];
    }

    if (empty($errors)) { // If everything's okay

        // Check if it's an update or insert operation
        if (isset($_POST['product_id'])) {
            // Update existing product
            $product_id = $_POST['product_id'];

            $query = "UPDATE products SET product_name='$product_name', product_description='$product_description' WHERE product_id='$product_id'";
        } else {
            // Insert new product
            $query = "INSERT INTO products (product_name, product_description) VALUES ('$product_name', '$product_description')";
        }

        $result = mysqli_query($dbc, $query); // Run the query

        if ($result) { // If it ran OK

            // Print a success message
            echo '<h1 id="mainhead">Success!</h1>
                <p>The product details have been ' . (isset($_POST['product_id']) ? 'updated' : 'added') . ' successfully.</p><p><br /></p>';

            // Include the footer and quit the script (to not show the form)
            include('./includes/footer.html');
            exit();
        } else { // If it did not run OK
            echo '<h1 id="mainhead">System Error</h1>
                <p class="error">The product details could not be ' . (isset($_POST['product_id']) ? 'updated' : 'added') . ' due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            include('./includes/footer.html');
            exit();
        }
    } else { // Report the errors
        echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    }

    mysqli_close($dbc); // Close the database connection
}

// If it's an update operation, fetch the product details
if (isset($_GET['product_id'])) {
    require_once('mysqli.php'); // Connect to the database

    $product_id = $_GET['product_id'];

    $query = "SELECT * FROM products WHERE product_id='$product_id'";
    $result = mysqli_query($dbc, $query); // Run the query

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $product_name = $row['product_name'];
        $product_description = $row['product_description'];
    } else {
        echo '<h1 id="mainhead">Error!</h1>
            <p class="error">Product not found.</p>';
        include('./includes/footer.html');
        exit();
    }

    mysqli_free_result($result); // Free up the result set
}
?>

<h2><?php echo isset($_GET['product_id']) ? 'Update' : 'Add'; ?> Product</h2>

<!-- Add Product Form -->
<?php if (!isset($_GET['product_id'])) { ?>
    <form action="AddOrUpdate.php" method="post">
        <p>Product Name: <input type="text" name="product_name" value="<?php echo isset($product_name) ? $product_name : ''; ?>" /></p>
        <p>Product Description: <textarea name="product_description"><?php echo isset($product_description) ? $product_description : ''; ?></textarea></p>
        <p><input type="submit" name="submit" value="Add Product" /></p>
    </form>
<?php } ?>

<!-- Update Product Form -->
<?php if (isset($_GET['product_id'])) { ?>
    <form action="AddOrUpdate.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
        <p>Product Name: <input type="text" name="product_name" value="<?php echo isset($product_name) ? $product_name : ''; ?>" /></p>
        <p>Product Description: <textarea name="product_description"><?php echo isset($product_description) ? $product_description : ''; ?></textarea></p>
        <p><input type="submit" name="submit" value="Update Product" /></p>
    </form>
<?php } ?>

<?php
include('./includes/footer.html');
?>