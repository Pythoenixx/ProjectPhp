<?php
    $page_title = 'Supplier Page';
include('./includes/header.php');

    require_once('mysqli.php'); // Connect to the db.
    
    global $dbc;

    $errors = [];
    // Check for supplier id.
    if (!isset($_SESSION['supplier_id'])) {
        $errors[] = 'Invalid supplier ID. Please log in again.';
    } else {
        $supplier_id = $_SESSION['supplier_id'];
        $supplier_name = $_SESSION['supplier_name'];
    }
?>
    <h1><?php echo "👨🏻‍💼$supplier_name" ?></h1>

    <form action="" method="POST">

        <?php
        echo <<<HTML
<p><input type="submit" name="changePassword" value="Change Password" /></p>
<p><input type="submit" name="logout" value="Logout" /></p>
</form>
HTML;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['logout'])) {
                // Redirect to the same page
                header("Location: Logout.php");
                exit();
            }
            if (isset($_POST['changePassword'])) {
                // Redirect to the same page
                header("Location: changePassword.php");
                exit();
            }
        }
        ?>


        <?php
        if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>

    <?php
    include('./includes/footer.html');
    ?>