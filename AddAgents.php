<?php
$page_title = 'Add New Agent';
include('./includes/header.html');
require_once('mysqli.php');
session_start();

function generateRandomPassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $password;
}

$query0 = "SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_id ASC";
$result = mysqli_query($dbc, $query0); // Run the query.
$num = mysqli_num_rows($result);

if ($num > 0) {
    echo "<p>There are currently $num registered suppliers.</p>\n";

    echo '<form action="AddAgents.php" method="post">';
    echo '<p>Agent ID: <input type="text" name="agent_id" size="15" maxlength="15" value="' . (isset($_POST['agent_id']) ? $_POST['agent_id'] : '') . '" /></p>';
    echo '<p>Agent Name: <input type="text" name="agent_name" size="15" maxlength="15" value="' . (isset($_POST['agent_name']) ? $_POST['agent_name'] : '') . '" /></p>';
    echo '<p>Contact Name: <input type="text" name="contact_name" size="15" maxlength="15" value="' . (isset($_POST['contact_name']) ? $_POST['contact_name'] : '') . '" /></p>';
    echo '<p>Contact Email: <input type="text" name="contact_email" size="15" maxlength="30" value="' . (isset($_POST['contact_email']) ? $_POST['contact_email'] : '') . '" /></p>';
    echo '<p>Contact Phone: <input type="text" name="contact_phone" size="15" maxlength="15" value="' . (isset($_POST['contact_phone']) ? $_POST['contact_phone'] : '') . '" /></p>';
    echo '<p><input type="submit" name="submit" value="Add Agent" /></p>';
    echo '</form>';

    mysqli_free_result($result);
} else {
    echo '<p class="error">There are currently no registered suppliers.</p>';
}

if (isset($_POST['submit'])) {
    $errors = [];

    // Check for agent ID.
    if (empty($_POST['agent_id'])) {
        $errors[] = 'You forgot to enter the agent ID.';
    } else {
        $agent_id = $_POST['agent_id'];

        // Validate agent ID format.
        if (!preg_match('/^5[0-9]{3}$/', $agent_id)) {
            $errors[] = 'Agent ID should start with 5 and have 4 digits.';
        }

        // Check if agent ID already exists.
        $query = "SELECT agent_id FROM agents WHERE agent_id='$agent_id'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors[] = 'Agent ID already exists. Please choose a different one.';
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

    // Check for supplier id.
    if (!isset($_SESSION['supplier_id'])) {
        $errors[] = 'Invalid supplier ID. Please log in again.';
    } else {
        $supplier_id = $_SESSION['supplier_id'];

        // Check if supplier ID exists.
        $query = "SELECT supplier_id FROM suppliers WHERE supplier_id='$supplier_id'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            $errors[] = 'Supplier ID does not exist. Please enter a valid supplier ID.';
        }
    }

    if (empty($errors)) {
        $password = generateRandomPassword();

        // Check for previous registration.
        $query = "SELECT agent_id FROM agents WHERE contact_email='$contact_email'";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            // Insert into agents table.
            $query = "INSERT INTO agents (agent_id, agent_name, contact_name, contact_email, contact_phone, supplier_id) 
                      VALUES ('$agent_id', '$agent_name', '$contact_name', '$contact_email', '$contact_phone', '$supplier_id')";
            $result = mysqli_query($dbc, $query);

            if ($result) {
                // Get the last inserted agent_id.
                $agent_id = mysqli_insert_id($dbc);

                // Insert into users table.
                $query = "INSERT INTO users (username, password, email, phone_number, registration_date, role) 
                VALUES ('$agent_name', '$password', '$contact_email', '$contact_phone', NOW(), 'agent')";

                $result = mysqli_query($dbc, $query);

                if ($result) {
                    // Print a success message.
                    echo '<h1 id="mainhead">Thank you!</h1>
                    <p>You have successfully added a new agent.</p>
                    <p>Password: ' . $password . '</p><p><br /></p>';

                    // Include the footer and quit the script (to not show the form).
                    include('./includes/footer.html');
                    exit();
                } else {
                    // If inserting into the users table failed.
                    echo '<h1 id="mainhead">System Error</h1>
                    <p class="error">The agent could not be added due to a system error. We apologize for any inconvenience.</p>';
                    echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>';
                    include('./includes/footer.html');
                    exit();
                }
            } else {
                // If inserting into the agents table failed.
                echo '<h1 id="mainhead">System Error</h1<p class="error">The agent could not be added due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>';
                include('./includes/footer.html');
                exit();
            }
        } else {
            // If the contact email is already registered.
            echo '<h1 id="mainhead">Duplicate Email</h1>
                <p class="error">The contact email you entered is already registered with another agent. Please enter a different email address.</p>';
        }
    } else {
        // If there are validation errors.
        echo '<h1 id="mainhead">Error!</h1>
        <p class="error">The following error(s) occurred:</p>';
        foreach ($errors as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
    }
}

mysqli_close($dbc);
include('./includes/footer.html');
?>
