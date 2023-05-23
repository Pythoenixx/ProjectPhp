<?php
$page_title = 'Main Page';
include('./includes/header.html');
?>
<div class="login-box">
    <form method="POST" action="MainPage.php">
        <div class="user-box"><input type="text" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" required="">
    <label>Username</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required="">
            <label>Password</label>
        </div>
        <div class="butang" style="margin-top: 20px;margin-bottom: 20px;">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <input type="submit" name="submit" value="Login" class="butang-teks">
        </div>
    </form>
    <div style="color: #fff;">Not Registered Yet ? <a href="Register.php">Register Here!</a>
    </div>
</div>



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