<?php
$page_title = 'Register as Supplier';
include('./includes/header.html');

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

    // Check for a first name.
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter your first name.';
    } else {
        $first_name = $_POST['first_name'];
    }

    // Check for a last name.
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $last_name = $_POST['last_name'];
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

        // Check for previous registration.
        $query = "SELECT user_id FROM users WHERE username='$username'";
        $result = @mysqli_query($dbc, $query); // Run the query.
        if (mysqli_num_rows($result) == 0) {

            // Insert into users table.
            $query = "INSERT INTO users (username, password, first_name, last_name, email, phone_number, registration_date, role) 
                      VALUES ('$username', '$password', '$first_name', '$last_name', '$email', '$phone_number', NOW(),  'supplier')";
            $result = @mysqli_query($dbc, $query); // Run the query.
            if ($result) { // If it ran OK.

                // Get the last inserted user_id.
                $user_id = mysqli_insert_id($dbc);

                // Generate the supplier_id.
                $query = "SELECT MAX(supplier_id) AS max_supplier_id FROM suppliers";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_assoc($result);
                $max_supplier_id = $row['max_supplier_id'];
                if ($max_supplier_id >= 8000 && $max_supplier_id <= 8999) {
                    $supplier_id = $max_supplier_id + 1;
                } else {
                    $supplier_id = 8000;
                }

                // Pad the supplier_id with leading zeros to ensure a minimum of 4 digits.
                $supplier_id = str_pad($supplier_id, 4, '0', STR_PAD_LEFT);

                // Insert into supplier table.
                $query = "INSERT INTO suppliers (supplier_id, supplier_name, contact_name, contact_email, contact_phone) 
                          VALUES ('$supplier_id', '$username', '$first_name $last_name', '$email', '$phone_number')";
                $result = mysqli_query($dbc, $query);

                if ($result) {
                    // Print a success message.
                    echo '<h1 id="mainhead">Thank you!</h1>
                    <p>You are now registered as a supplier.</p><p><br /></p>';

                    // Include the footer and quit the script (to not show the form).
                    include('./includes/footer.html');
                    exit();
                } else {
                    // If inserting into the supplier table failed.
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
        } else { // Already registered.
            echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The username has already been registered.</p>';
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
<h2>Register as Supplier</h2>
<form action="register.php" method="post">
    <p>Username: <input type="text" name="username" size="15" maxlength="15" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></p>
    <p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
    <p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
    <p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
    <p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
    <p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></p>
    <p>Phone Number: <input type="text" name="phone_number" size="15" maxlength="15" value="<?php if (isset($_POST['phone_number'])) echo $_POST['phone_number']; ?>" /></p>
    <p><input type="submit" name="submit" value="Register" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include('./includes/footer.html');
?>
