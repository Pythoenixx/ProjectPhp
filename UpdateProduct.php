<?php
$page_title = 'Add Product';
include('./includes/header.html');

require_once('mysqli.php'); // Connect to the db.

global $dbc;

$query0 = "SELECT product_id, product_name, product_description, price, cost FROM products";
$result = mysqli_query($dbc, $query0); // Run the query.
$num = mysqli_num_rows($result) OR die('SQL Statement: ' . mysqli_error($dbc));

if ($num > 0) { // If it ran OK, display the records.

    echo "<p>There are currently $num products.</p>\n";

    // Table header.
    echo '<table align="center" cellspacing="0" cellpadding="5">
        <tr>
            <td align="left"><b>Product ID</b></td>
            <td align="left"><b>Product Name</b></td>
            <td align="left"><b>Product Description</b></td>
            <td align="left"><b>Price</b></td>
            <td align="left"><b>Cost</b></td>
        </tr>';

    // Fetch and print all the records.
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo '<tr>
            <td align="left">' . $row['product_id'] . '</td>
            <td align="left">' . $row['product_name'] . '</td>
            <td align="left">' . $row['product_description'] . '</td>
            <td align="left">' . $row['price'] . '</td>
            <td align="left">' . $row['cost'] . '</td>
            <td align="left">
                
            </td>
        </tr>';
    }

    echo '</table>';

    mysqli_free_result($result); // Free up the resources.

} else { // If it did not run OK.
    echo '<p class="error">There are currently no products.</p>';
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve the submitted data
    $errors = [];

    if (($_POST['product_id'] == "")) {
        $errors[] = 'You forgot to enter product id.';
    } else {
        $product_id = $_POST['product_id'];
    }
    if (empty($_POST['product_name'])) {
        $errors[] = 'You forgot to enter product name.';
    } else {
        $product_name = $_POST['product_name'];
    }
    if (empty($_POST['product_description'])) {
        $errors[] = 'You forgot to enter product description.';
    } else {
        $product_description = $_POST['product_description'];
    }
    if (empty($_POST['price'])) {
        $errors[] = 'You forgot to enter price.';
    } else {
        $price = $_POST['price'];
    }
    if (empty($_POST['cost'])) {
        $errors[] = 'You forgot to enter cost.';
    } else {
        $cost = $_POST['cost'];
    }

    if (empty($errors)) {
        // Update the product details in the database
        $query = "UPDATE products SET product_name = '$product_name', product_description = '$product_description', 
              price = '$price', cost = '$cost' WHERE product_id = $product_id";
        $result = mysqli_query($dbc, $query);

        if ($result) {
            echo '<p class="success">Product details updated successfully.</p>';
        } else {
            echo '<p class="error">Error updating product details: ' . mysqli_error($dbc) . '</p>';
        }
    } else {
        echo '<p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $error) {
            echo "- $error<br />";
        }
        echo '</p>';
    }
}
if (isset($_POST['refresh'])) {
    // Redirect to the same page
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
if (isset($_POST['logout'])) {
    // Redirect to the same page
    header("Location: Logout.php");
    exit();
}
?>
<form method="post" action="UpdateProduct.php">
    <p><input type="text" name="product_id" placeholder="Product Id" value="<?php if (isset($_POST['product_id'])) echo $_POST['product_id']; ?>"></p>
    <p><input type="text" name="product_name" placeholder="Product Name" value="<?php if (isset($_POST['product_name'])) echo $_POST['product_name']; ?>"></p>
    <p><input type="text" name="product_description" placeholder="Product Description" value="<?php if (isset($_POST['product_description'])) echo $_POST['product_description']; ?>"></p>
    <p><input type="text" name="price" placeholder="Price" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>"></p>
    <p><input type="text" name="cost" placeholder="Cost" value="<?php if (isset($_POST['cost'])) echo $_POST['cost']; ?>"></p>
    <p><input type="submit" name="submit" value="Update"></p>
    <input type="submit" name="refresh" value="Refresh">
    <p><input type="submit" name="logout" value="Logout"></p>
</form>
<?php
include('./includes/footer.html');
?>
