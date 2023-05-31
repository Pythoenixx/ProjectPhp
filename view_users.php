<?php
$page_title = 'Supplier Page';
include('./includes/header.html');

require_once('mysqli.php'); // Connect to the db.
session_start();
global $dbc;

$errors = [];
if (!isset($_SESSION['role'])) {
    $errors[] = 'Invalid role. Please log in again.';
} else {
    $role = $_SESSION['role'];
}
if ($role == 'supplier') {
    // Check for supplier id.
    if (!isset($_SESSION['supplier_id'])) {
        $errors[] = 'Invalid supplier ID. Please log in again.';
    } else {
        $supplier_id = $_SESSION['supplier_id'];
        $supplier_name = $_SESSION['supplier_name'];
    }
    ?>
    <h1><?php echo "👨🏻‍💼$supplier_name" ?></h1>

    <form action="" method="POST">

        <?php
        if (empty($errors)) { // Make the query.
            $query = "SELECT agent_id, agent_name, contact_name, contact_email, contact_phone, supplier_id FROM agents WHERE supplier_id = $supplier_id ORDER BY agent_id ASC";
            $result = @mysqli_query($dbc, $query); // Run the query.
            $num = @mysqli_num_rows($result);

            if ($num > 0) { // If it ran OK, display the records.

                echo "<p>There are currently {$num} registered agents under {$supplier_name}.</p>\n";

                // Table header.
                echo '<table align="center" cellspacing="0" cellpadding="5">
                    <tr><td align="left"><b>Agent ID</b></td><td align="left"><b>Agent Name</b></td><td align="left"><b>Username</b></td><td align="left"><b>Contact Email</b></td><td align="left"><b>Contact Phone</b></td><td align="left"><b>Supplier ID</b></td></tr>';

                // Fetch and print all the records.
                while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<tr>';
                    echo '<td align="left">' . $row['agent_id'] . '</td>';
                    echo '<td align="left">' . $row['agent_name'] . '</td>';
                    echo '<td align="left">' . $row['contact_name'] . '</td>';
                    echo '<td align="left">' . $row['contact_email'] . '</td>';
                    echo '<td align="left">' . $row['contact_phone'] . '</td>';
                    echo '<td align="left">' . $row['supplier_id'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';

                @mysqli_free_result($result); // Free up the resources.
            } else { // If it did not run OK.
            echo '<p class="error">There are currently no registered agents under you.</p>';
            }

            @mysqli_close($dbc); // Close the database connection.
        }
        echo <<<HTML
            <div class="tengah" style="display: flex;justify-content: center;flex-direction: row;">
                <div class="butang">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <input type="submit" name="regAgent" value="REGISTER NEW AGENT" class="butang-teks">
                </div>
            </div>
        </form>
        HTML;


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['regAgent'])) {
                // Redirect to the same page
                header("Location: AddAgents.php");
                exit();
            }
        }
        ?>


    <?php
    if (!empty($errors)) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
}else {
    if (!isset($_SESSION['agent_id'])) {
        $errors[] = 'Invalid agent ID. Please log in again.';
    } else {
        $agent_id = $_SESSION['agent_id'];
        $agent_name = $_SESSION['agent_name'];
        $agent_punya_supplier = $_SESSION['agent_supplier'];
    }

    echo "<h1>🤵🏿$agent_name</h1>";

    $query = "SELECT agent_id, agent_name, contact_name, supplier_id FROM agents WHERE supplier_id = $agent_punya_supplier ORDER BY agent_id ASC";
    $result = @mysqli_query($dbc, $query); // Run the query.
    $num = @mysqli_num_rows($result) or die('SQL Statement: ' . mysqli_error($dbc));

    if ($num > 0) { // If it ran OK, display the records.

        echo "<p>There are currently {$num} registered agents under your supplier.</p>\n";

        // Table header.
        echo '<table align="center" cellspacing="0" cellpadding="5">
            <tr><td align="left"><b>Agent ID</b></td><td align="left"><b>Agent Name</b></td><td align="left"><b>Username</b></td><td align="left"><b>Supplier ID</b></td></tr>';

        // Fetch and print all the records.
        while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<tr>';
            echo '<td align="left">' . $row['agent_id'] . '</td>';
            echo '<td align="left">' . $row['agent_name'] . '</td>';
            echo '<td align="left">' . $row['contact_name'] . '</td>';
            echo '<td align="left">' . $row['supplier_id'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';

        @mysqli_free_result($result); // Free up the resources.
    } else { // If it did not run OK.
        echo '<p class="error">There are currently no registered agents.</p>';
    }

    @mysqli_close($dbc); // Close the database connection.
}
    ?>

    <?php
    include('./includes/footer.html');
    ?>