<?php
$page_title = 'Add New Agent';
include ('./includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submit'])) {

    require_once('mysqli.php'); // Connect to the db.

    $errors = [];  // Initialize error array.

    // Check for agent ID.
    if (empty($_POST['agent_id'])) {
        $errors[] = 'You forgot to enter the agent ID.';
    } else {
        $agent_id = $_POST['agent_id'];

        // Validate agent ID format.
        if (!preg_match('/^5[0-9]{3}$/', $agent_id)) {
            $errors[] = 'Agent ID should start with 5 and have 4 digits.';
        }
    }

    // Check for agent name.
    if (empty($_POST['agent_name'])) {
        $errors[] = 'You forgot to enter the agent name.';
    } else {
        $agent_name = $_POST['agent_name'];
    }

    // Check for contact name.
    if (empty($_POST['contact_name'])) {
        $errors[] = 'You forgot to enter the contact name.';
    } else {
        $contact_name = $_POST['contact_name'];
    }

    // Check for contact email.
    if (empty($_POST['contact_email'])) {
        $errors[] = 'You forgot to enter the contact email.';
    } else {
        $contact_email = $_POST['contact_email'];
    }

    // Check for contact phone.
    if (empty($_POST['contact_phone'])) {
        $errors[] = 'You forgot to enter the contact phone.';
    } else {
        $contact_phone = $_POST['contact_phone'];
    }

    if (empty($errors)) { // If everything's okay.

        // Register the agent in the database.

        // Check for previous registration.
        $query = "SELECT agent_id FROM agents WHERE contact_email='$contact_email'";
        $result = mysqli_query($dbc, $query); // Run the query.
        if (mysqli_num_rows($result) == 0) {

            // Make the query.
            $query = "INSERT INTO agents (agent_id, agent_name, contact_name, contact_email, contact_phone) 
                  VALUES ('$agent_id', '$agent_name', '$contact_name', '$contact_email', '$contact_phone')";
            $result = mysqli_query($dbc, $query); // Run the query.

            if ($result) { // If it ran OK.

                // Print a message.
                echo '<h1 id="mainhead">Thank you!</h1>
                <p>You have successfully added a new agent.</p><p><br /></p>';

                // Include the footer and quit the script (to not show the form).
                include ('./includes/footer.html');
                exit();

            } else { // If it did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
                <p class="error">The agent could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                include ('./includes/footer.html');
                exit();
            }

        } else { // Already registered.
            echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The contact email has already been registered.</p>';
        }

    } else { // Report the errors.
        echo '<h1 id="mainhead">Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
    }

    mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<h2>Add New Agent</h2>

<form action="AddAgents.php" method="post">
    <p>Agent ID: <input type="text" name="agent_id" value="<?php if (isset($_POST['agent_id'])) echo $_POST['agent_id']; ?>" /></p>
    <p>Agent Name: <input type="text" name="agent_name" value="<?php if (isset($_POST['agent_name'])) echo $_POST['agent_name']; ?>" /></p>
    <p>Contact Name: <input type="text" name="contact_name" value="<?php if (isset($_POST['contact_name'])) echo $_POST['contact_name']; ?>" /></p>
    <p>Contact Email: <input type="text" name="contact_email" value="<?php if (isset($_POST['contact_email'])) echo $_POST['contact_email']; ?>" /></p>
    <p>Contact Phone: <input type="text" name="contact_phone" value="<?php if (isset($_POST['contact_phone'])) echo $_POST['contact_phone']; ?>" /></p>
    <p><input type="submit" name="submit" value="Add Agent" /></p>
</form>

<?php
include ('./includes/footer.html');
?>