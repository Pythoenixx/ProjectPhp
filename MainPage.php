<?php
$page_title = 'Main Page';
session_start();
?>
<link rel="stylesheet" type="text/css" href="includes/efek.css" />

<div class="login-box">
<h2>Login apa jadi kalau ada dua pull request?</h2>
    <form method="POST" action="MainPage.php">
        <div class="user-box">
            <input type="text" name="username" required=""value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" >
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
    <div style="color: #fff;">Not Registered Yet? <a href="Register.php">Register Here!</a></div>
</div>

<?php
require_once('mysqli.php'); // Connect to the db.
global $dbc;

$errors = [];

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
                // Get the agent's ID from the agents table
                $agent_username = $row['username'];
                $query = "SELECT agent_id FROM agents WHERE agent_name='$agent_username'";
                $result = $dbc->query($query);

                if ($result->num_rows > 0) {
                    $agent_row = $result->fetch_assoc();
                    $_SESSION['agent_id'] = $agent_row['agent_id'];

                    header("Location: agents_page.php");
                    exit();
                }
            } elseif ($role === 'supplier') {
                // Get the supplier's ID from the suppliers table
                $supplier_username = $row['username'];
                $query = "SELECT supplier_id FROM suppliers WHERE supplier_name='$supplier_username'";
                $result = $dbc->query($query);

                if ($result->num_rows > 0) {
                    $supplier_row = $result->fetch_assoc();
                    $_SESSION['supplier_id'] = $supplier_row['supplier_id'];

                    header("Location: supplier_page.php");
                    exit();
                }
            }
        } else {
            $errors[] = "Invalid username or password.";
        }
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
