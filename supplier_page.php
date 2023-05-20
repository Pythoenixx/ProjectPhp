<?php
$page_title = 'Supplier Page';
include ('./includes/header.html');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["option"])) {
        $option = $_POST["option"];

        if (empty($option)) {
            $errors[] = "Please select an option.";
        } else {
            if ($option == "register_agent") {
                header("Location: AddAgents.php");
                exit();
            } elseif ($option == "add_product") {
                header("Location: AddOrUpdate.php");
                exit();
            }
        }
    } else {
        $errors[] = "Please select an option.";
    }
}
?>

<h1>Supplier Page</h1>

<form action="" method="POST">
    <label>
        <p><input type="radio" name="option" value="register_agent"> Register New Agent</p>
    </label>
    <label>
        <p><input type="radio" name="option" value="add_product"> Add/Update Product Details</p>
    </label>
    <button type="submit" name="submit">Proceed</button>
    
    <?php
    if (!empty($errors)) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
    ?>
</form>

<?php
include ('./includes/footer.html');
?>