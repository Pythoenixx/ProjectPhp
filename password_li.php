<?php # Script 7.8 - password.php
// This page lets a user change their password.

// Set the page title and include the HTML header.
$page_title = 'Change Your Password';
include ('./includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {


	require_once ('mysqli.php'); // Connect to the db.
		
	global $dbc;
		

	$errors = array(); // Initialize error array.
	
	// Check for an email address.
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = $_POST['email'];
	}
	
	// Check for an existing password.
	if (empty($_POST['password'])) {
		$errors[] = 'You forgot to enter your existing password.';
	} else {
		$p = $_POST['password'];
	}

	// Check for a password and match against the confirmed password.
	if (!empty($_POST['password1'])) {
		if ($_POST['password1'] != $_POST['password2']) {
			$errors[] = 'Your new password did not match the confirmed new password.';
		} else {
			$np = $_POST['password1'];
		}
	} else {
		$errors[] = 'You forgot to enter your new password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Check that they've entered the right email address/password combination.
		$query = "SELECT user_id FROM users WHERE (email='$e' AND password=SHA('$p') )";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the user_id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the UPDATE query.
			$query = "UPDATE users SET password=SHA('$np') WHERE user_id=$row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
			
				// Send an email, if desired.
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Your password has been updated. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid email address/password combination.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The email address and password do not match those on file.</p>';
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
<h2>Change Your Password</h2>
<form action="password_li.php" method="post">
	<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Current Password: <input type="password" name="password" size="10" maxlength="20" /></p>
	<p>New Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
	<p>Confirm New Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Register" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include ('./includes/footer.html');
?>