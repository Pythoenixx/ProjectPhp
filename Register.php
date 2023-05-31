<?php
$page_title = 'Register as Supplier';

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    require_once('mysqli.php'); // Connect to the db.

    global $dbc;

    $errors = array(); // Initialize error array.

    // Check for a username.
    if (empty($_POST['username'])) {
        $errors[] = 'You forgot to enter your username.';
    } else {
        $username = $_POST['username'];
        // Check for a unique username.
        $query = "SELECT user_id FROM users WHERE username='$username'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = 'The username is already taken. Please choose a different username.';
        }
    }

    // Check for a password and match against the confirmed password.
    if (!empty($_POST['password1'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $password = $_POST['password1'];
        }
    } else {
        $errors[] = 'You forgot to enter your password.';
    }

    // Check for an email address.
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $email = $_POST['email'];
    }

    // Check for a phone number.
    if (empty($_POST['phone_number'])) {
        $errors[] = 'You forgot to enter your phone number.';
    } else {
        $phone_number = $_POST['phone_number'];
    }

    if (empty($errors)) { // If everything's okay.

        // Register the user in the database.

        // Insert into users table.
        $query = "INSERT INTO users (username, password, email, phone_number, registration_date, role) 
                  VALUES ('$username', '$password', '$email', '$phone_number', NOW(), 'supplier')";
        $result = mysqli_query($dbc, $query); // Run the query.
        if ($result) { // If it ran OK.

            // Get the last inserted user_id.
            $user_id = mysqli_insert_id($dbc);

            // Insert into suppliers table.
            $query = "INSERT INTO suppliers (supplier_name, contact_email, contact_phone) 
                      VALUES ('$username', '$email', '$phone_number')";
            $result = mysqli_query($dbc, $query);

            if ($result) {
                // Get the inserted supplier_id.
                $supplier_id = mysqli_insert_id($dbc);

                // Print a success message.
                echo '<h1 id="mainhead">Thank you!</h1>
                <p>You are now registered as a supplier.</p><p><br /></p>';

                // Include the footer and quit the script (to not show the form).
                include('./includes/footer.html');
                exit();
            } else {
                // If inserting into the suppliers table failed.
                echo '<h1 id="mainhead">System Error</h1>
                <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                include('./includes/footer.html');
                exit();
            }
        } else { // If inserting into the users table failed.
            echo '<h1 id="mainhead">System Error</h1>
            <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            include('./includes/footer.html');
            exit();
        }
    } else { // Report the errors.
        echo '<h1 id="mainhead">Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    } // End of if (empty($errors)) IF.
    mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.
?>
<link rel="stylesheet" type="text/css" href="includes/efek.css" />
<div class="login-box">
    <h2>Register as Supplier</h2>
    <form action="register.php" method="post">
        <div class="user-box">
            <input type="text" name="username" required maxlength="15" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" />
            <label>Username</label>
        </div>
        <div class="user-box">
            <input type="password" name="password1" required maxlength="20" />
            <label>Password</label>
        </div>
        <div class="user-box">
            <input type="password" name="password2" required maxlength="20" />
            <label>Confirm Password</label>
        </div>
        <div class="user-box">
            <input type="text" name="email" required maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
            <label>Email</label>
        </div>
        <div class="user-box">
            <input type="text" name="phone_number" required maxlength="15" value="<?php if (isset($_POST['phone_number'])) echo $_POST['phone_number']; ?>" />
            <label>Phone Number</label>
        </div>
        <div class="butang">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <input type="submit" name="submit" value="Register" class="butang-teks" />
        </div>
        <input type="hidden" name="submitted" value="TRUE" />
    </form>
</div>
<?php
include('./includes/footer.html');
?>
