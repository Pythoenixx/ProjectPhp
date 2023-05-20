<form method="POST" action="MainPage.php">
    <p><input type="text" name="id" placeholder="ID" value="<?php if (isset($_POST['id'])) echo $_POST['id']; ?>">
    <p><input type="text" name="contact_name" placeholder="Contact Name" value="<?php if (isset($_POST['contact_name'])) echo $_POST['contact_name']; ?>"></p>
    <p><input type="submit" name="submit" value="Login"></p>
</form>
<?php

require_once('mysqli.php'); // Connect to the db.
global $dbc;

$errors = []; // Declare $errors variable outside the if statement

if (isset($_REQUEST['submit'])) {
    // Retrieve login form data
    $id = $_POST['id'];
    $contact_name = $_POST['contact_name'];

    if (empty($id)) {
        $errors[] = "ID is required.";
    }

    if (empty($contact_name)) {
        $errors[] = "Contact name is required.";
    }

    if (count($errors) === 0) {
        // Check if ID starts with 5 (for agents)
        if (substr($id, 0, 1) === '5') {
            // Check agents table
            $query = "SELECT * FROM agents WHERE agent_id='$id' AND contact_name='$contact_name'";
            $result = $dbc->query($query);

            if ($result->num_rows > 0) {
                // Valid agent found
                // Redirect to the agents page
                header("Location: agents_page.php");
                exit();
            }
        }

        // Check if ID starts with 8 (for suppliers)
        if (substr($id, 0, 1) === '8') {
            // Check suppliers table
            $query = "SELECT * FROM suppliers WHERE supplier_id='$id' AND contact_name='$contact_name'";
            $result = $dbc->query($query);

            if ($result->num_rows > 0) {
                // Valid supplier found
                // Redirect to the suppliers page
                header("Location: supplier_page.php");
                exit();
            }
        }

        $errors[] = "Invalid ID or contact name.";
    }
}

$dbc->close();
?>

<?php
if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo '<p style="color: red;">' . $error . '</p>';
    }
}
?>