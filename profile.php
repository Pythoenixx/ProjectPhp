<?php
session_start();
include('./includes/header.html');
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
$agentName = "";
$supplierName = "";
$contactName = "";
$contactEmail = "";
$contactPhone = "";

// Retrieve user data
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = $dbc->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $phone = $row['phone_number'];
    $role = $row['role'];

    if ($role === 'agent') {
        // Retrieve agent data
        $query = "SELECT * FROM agents WHERE agent_id='$row[agent_id]'";
        $result = $dbc->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $agentName = $row['agent_name'];
            $contactName = $row['contact_name'];
            $contactEmail = $row['contact_email'];
            $contactPhone = $row['contact_phone'];
        }
    } elseif ($role === 'supplier') {
        // Retrieve supplier data
        if (isset($row['supplier_id'])) { // Check if 'supplier_id' key exists in $row array
            $query = "SELECT * FROM suppliers WHERE supplier_id='$row[supplier_id]'";
            $result = $dbc->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $supplierName = $row['supplier_name'];
                $contactName = $row['contact_name'];
                $contactEmail = $row['contact_email'];
                $contactPhone = $row['contact_phone'];
            }
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
    $query = "SELECT * FROM users WHERE username='$newUsername' OR email='$newEmail'";
    $result = $dbc->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the existing username is the same as the current user's username
        if ($row['username'] !== $username) {
            $errors[] = "Username already exists.";
        }

        // Check if the existing email is the same as the current user's email
        if ($row['email'] !== $email) {
            $errors[] = "Email already exists.";
        }
    }

    if (count($errors) === 0) {
        // Update user data in the database
        $query = "UPDATE users SET username='$newUsername', email='$newEmail', phone_number='$newPhone', password='$newPassword' WHERE username='$username'";
        $dbc->query($query);
    
        // Update agent data in the database
        if ($role === 'agent') {
            // Update agent data in the database using the username
            $query = "UPDATE agents SET agent_name='$newUsername', contact_email='$newEmail', contact_phone='$newPhone' WHERE agent_name='$username'";
            $dbc->query($query);
        }
    
        // Update supplier data in the database
        if ($role === 'supplier') {
            // Update supplier data in the database using the username
            $query = "UPDATE suppliers SET supplier_name='$newUsername', contact_email='$newEmail', contact_phone='$newPhone' WHERE supplier_id='$row[supplier_id]'";
            $dbc->query($query);
        }
    
        // Set the success message
        $successMessage = "Profile updated successfully.";
    
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
    <div>
        <label>Username:</label>
        <input type="text" name="newUsername" value="<?php echo $username; ?>" >
    </div>
    <div>
        <label>Email:</label>
        <input type="email" name="newEmail" value="<?php echo $email; ?>" >
    </div>
    <div>
        <label>Phone number:</label>
        <input type="text" name="newPhone" value="<?php echo $phone; ?>" >
    </div>
    <div>
        <label>New Password:</label>
        <input type="password" name="newPassword" >
    </div>
    <div>
    <input type="submit" name="update" value="Updates" class="butang-teks">
    </div>
</form>

<?php
include('./includes/footer.html');
?>
