<?php
include('./includes/header.php');
$page_title = 'Profile Page';

// Check if user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: MainPage.php");
    exit();
}

require_once('mysqli.php'); // Connect to the db.
global $dbc;

// Initialize variables
$email = "";
$phone = "";
$supplierName = "";
$agentName = "";

// Retrieve user data
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = $dbc->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $phone = $row['phone_number'];
}

// Retrieve supplier or agent data based on the user's role
$query = "";
if ($_SESSION['role'] === 'supplier') {
    $query = "SELECT * FROM suppliers WHERE supplier_name='$username'";
} elseif ($_SESSION['role'] === 'agent') {
    $query = "SELECT * FROM agents WHERE agent_name='$username'";
}

if ($query !== "") {
    $result = $dbc->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($_SESSION['role'] === 'supplier') {
            $supplierEmail = $row['contact_email'];
            $supplierPhone = $row['contact_phone'];
        } elseif ($_SESSION['role'] === 'agent') {
            $agentEmail = $row['contact_email'];
            $agentPhone = $row['contact_phone'];
        }
    }
}

$errors = [];
$successMessage = '';

if (isset($_POST['update'])) {
    // Retrieve form data
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newPhone = $_POST['newPhone'];
    $newPassword = $_POST['newPassword'];

    // Validate form data
    if (empty($newUsername)) {
        $errors[] = "Username is required.";
    }

    if (empty($newEmail)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($newPhone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $newPhone)) {
        $errors[] = "Invalid phone number format. Phone number must be 10 digits.";
    }

    if (empty($newPassword)) {
        $errors[] = "Password is required.";
    }

    // Check if the new username or email already exists
    $query = "SELECT * FROM users WHERE (username='$newUsername' OR email='$newEmail') AND username != '$username'";
    $result = $dbc->query($query);

    if ($result->num_rows > 0) {
        $errors[] = "Username or email already exists.";
    }

    if (count($errors) === 0) {
        // Update supplier or agent data in the database based on the user's role
        if ($_SESSION['role'] === 'supplier') {
            $query = "UPDATE suppliers SET supplier_name='$newUsername', contact_email='$newEmail', contact_phone='$newPhone' WHERE supplier_name='$username'";
        } elseif ($_SESSION['role'] === 'agent') {
            $query = "UPDATE agents SET agent_name='$newUsername', contact_email='$newEmail', contact_phone='$newPhone' WHERE agent_name='$username'";
        }
        $dbc->query($query);

        // Update user data in the database
        $query = "UPDATE users SET username='$newUsername', email='$newEmail', phone_number='$newPhone', password='$newPassword' WHERE username='$username'";
        $dbc->query($query);

        // Set the success message
        $successMessage = "Profile updated successfully.";

        // Update the session username if it was changed
        if ($newUsername !== $username) {
            $_SESSION['username'] = $newUsername;
        }

        // Redirect to the profile page after successful update
        header("Location: profile.php");
        exit();
    }
}

$dbc->close();
?>

<!-- HTML form on the profile page -->
<h2>Update Profile</h2>

<?php
if ($successMessage !== '') {
    echo '<p style="color: green;">' . $successMessage . '</p>';
}
?>

<form method="POST" action="profile.php">
    <p>
        <div>
            <label>Username:</label>
            <input type="text" name="newUsername" value="<?php echo $username; ?>">
        </div>
    </p>
    <p>
        <div>
            <label>Email:</label>
            <input type="email" name="newEmail" value="<?php echo $email; ?>">
        </div>
    </p>
    <p>
        <div>
            <label>Phone number:</label>
            <input type="text" name="newPhone" value="<?php echo $phone; ?>">
        </div>
    </p>
    <p>
        <div>
            <label>New Password:</label>
            <input type="password" name="newPassword">
        </div>
    </p>
    <p>
        <div>
            <input type="submit" name="update" value="Update" class="butang-teks">
        </div>
    </p>
</form>
<form method="POST" action="logout.php">
    <p>
        <div>
            <input type="submit" name="logout" value="Logout" class="butang-teks">
        </div>
    </p>
</form>

<?php
include('./includes/footer.html');
?>

