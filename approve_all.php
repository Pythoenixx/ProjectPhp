<?php
// Connect to the database
require_once('mysqli.php');
global $dbc;

// Check if the form is submitted 
if (isset($_POST['approve_all'])) {
    // Get the selected customer name
    $selectCustomer = isset($_POST['approve_select']) ? $_POST['approve_select'] : '';

    // Update the status for the selected customer(s)
    if ($selectCustomer === '') {
        // kalau all statement selected semua akan diifect
        $sql = "UPDATE orders SET status='APPROVE' WHERE status='PENDING'";
    } else {
        // Update status for the selected customer
        $selectCustomer = mysqli_real_escape_string($dbc, $selectCustomer);
        $sql = "UPDATE orders SET status='APPROVE' WHERE customer_name='$selectCustomer' AND status='PENDING'";
    }

    // Execute the SQL query
    if ($dbc->query($sql) === false) {
        // Handle query error
        echo "Error: " . $dbc->error;
        exit();
    }

    mysqli_close($dbc); // Close the database connection.

    // Redirect to the order management page
    header('Location: order_list.php');
    exit();
}

?>