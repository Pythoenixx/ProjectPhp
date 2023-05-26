<?php
session_start();

// Check if the agent is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: MainPage.php");
    exit();
}

$page_title = 'Change Password';
include('./includes/header.html');

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('mysqli.php');
    global $dbc;

    // Validate the current password
    if (empty($_POST['current_password'])) {
        $errors[] = 'Please enter your current password.';
    } else {
        $current_password = mysqli_real_escape_string($dbc, $_POST['current_password']);
        $username = $_SESSION['username'];

        // Check if the current password is correct
        $query = "SELECT password FROM users WHERE username = '$username'";
        $result = $dbc->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            // Verify the current password
            if ($current_password !== $stored_password) {
                $errors[] = 'The current password is incorrect.';
            }
        } else {
            $errors[] = 'Error: Unable to verify current password.';
        }
    }

    // Validate the new password
    if (empty($_POST['new_password'])) {
        $errors[] = 'Please enter a new password.';
    } elseif (strlen($_POST['new_password']) < 8) {
        $errors[] = 'The new password must be at least 8 characters long.';
    } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
        $errors[] = 'The new password and confirm password do not match.';
    } else {
        $new_password = mysqli_real_escape_string($dbc, $_POST['new_password']);
    }

    // Update the password if there are no errors
    if (empty($errors)) {
        // Update the password in the users table
        $query = "UPDATE users SET password = '$new_password' WHERE username = '$username'";
        $result = $dbc->query($query);

        if ($result) {
            // Password updated successfully
            echo '<p>Your password has been successfully updated.</p>';
        } else {
            $errors[] = 'Error updating password. Please try again.';
        }
    }
}

?>

<h1>Change Password</h1>

<?php
if (!empty($errors)) {
    echo '<div class="error">';
    foreach ($errors as $error) {
        echo '<p>' . $error . '</p>';
    }
    echo '</div>';
}
?>

<form method="post" action="changePassword.php">
    <p><label for="current_password">Current Password:</label>
    <input type="password" name="current_password" id="current_password" ><br></p>

    <p><label for="new_password">New Password:</label>
    <input type="password" name="new_password" id="new_password" ><br></p>

    <p><label for="confirm_password">Confirm Password:</label>
    <input type="password" name="confirm_password" id="confirm_password" ><br></p>

    <p><input type="submit" value="Change Password"></p>
</form>

<?php
include('./includes/footer.html');
?>
