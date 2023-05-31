<?php
session_start();
$page_title = 'Logout';
?>
<link rel="stylesheet" type="text/css" href="includes/efek.css" />
<main>
    <h1>You have been logout ! </h1>
    <h2>Thank You for using us :></h2>
<h5>You will be redirected to the login page in 2.0 seconds</h5>
</main>
<?php
session_unset();
session_destroy();

header('Refresh: 2.0; MainPage.php'); 
?>