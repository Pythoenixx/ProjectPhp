<?php
$page_title = 'Main Page';
include('./includes/header.html');
?>
<form method="POST" action="MainPage.php">
    <p><input type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
    <p><input type="password" name="password" placeholder="Password"></p>
    <p><input type="submit" name="submit" value="Login"></p>
</form>
<form method="POST" action="Register.php"> 
    <p>Not Registered Yet ? Click Below !</p>
    <p><input type="submit" name="register" value="Register"></p>
</form>


<?php

require_once('mysqli.php'); // Connect to the db.
global $dbc;

$errors = []; // Declare $errors variable outside the if statement

if (isset($_REQUEST['submit'])) {
    // Retrieve login form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (count($errors) === 0) {
        // Check user table for username and password
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $dbc->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $role = $row['role'];

            // Redirect based on the user's role
            if ($role === 'agent') {
                // Redirect to the agents page
                header("Location: agents_page.php");
                exit();
            } elseif ($role === 'supplier') {
                // Redirect to the suppliers page
                header("Location: supplier_page.php");
                exit();
            }
        }

        $errors[] = "Invalid username or password.";
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
include('./includes/footer.html');
?>