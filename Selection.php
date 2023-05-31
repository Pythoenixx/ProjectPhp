<?php
$page_title = 'Selection Page';
include('./includes/header.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (isset($_POST['option']) && $_POST['option'] === '1') {
        header("Location: AddProduct.php");
        exit();
    } else if (isset($_POST['option']) && $_POST['option'] === '2') {
        header("Location: UpdateProduct.php");
        exit();
    } else {
        $errors[] = 'Please select an option.';
    }

    if (!empty($errors)) {
        echo '<p class="error">' . implode('<br>', $errors) . '</p>';
    }
}
if (isset($_POST['logout'])) {
    // Redirect to the same page
    header("Location: Logout.php");
    exit();
}
?>

<form method="POST" action="Selection.php">
    <label for="option1">
    <p><input type="radio" name="option" value="1" id="option1"> Add Product</p>
    </label><br>
    <label for="option2">
    <p><input type="radio" name="option" value="2" id="option2"> Update Product</p>
    </label><br>
    <p><button type="submit">Submit</button></p>
    <p><button type="logout" name="logout">Logout</button></p>
</form>

<?php
include('./includes/footer.html');
?>