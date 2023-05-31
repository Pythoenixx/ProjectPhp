<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="includes/efek.css" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $page_title; ?></title>
</head>
<body>
<div id="wrapper"><!-- Goes with the CSS layout. -->
<?php
session_start();
$errors = [];
if (!isset($_SESSION['role'])) {
    $errors[] = 'Invalid role. Please log in again.';
} else {
    $role = $_SESSION['role'];
}
if ($role == 'supplier') {
  $emoji = '👨🏻‍💼';
} elseif ($role == 'agent') {
  $emoji = '🤵🏿';
}
?>
	<div id="content"><!-- Goes with the CSS layout. -->
			    <nav class="navbar">
      <ul class="navbar-nav">
        <div class="all-item">
        <li class="nav-item">
          <a href="Profile.php" class="nav-link">
            <div class="emoji">
            <?php echo $emoji; ?></div>
        <span class="link-text">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="view_users.php" class="nav-link">
            <div class="emoji">
            👥</div>
            <span class="link-text">Users</span>
          </a>
        </li>
		<li class="nav-item">
			<a href="view_product.php" class="nav-link">
			  <div class="emoji">
			  🎮</div>
			  <span class="link-text">Products</span>
			</a>
		  </li>
		  <li class="nav-item">
			<a href="order_list.php" class="nav-link">
			  <div class="emoji">
				🧾</div>
			  <span class="link-text">Orders</span>
			</a>
		  </li>
        <li class="nav-item">
        <a href="viewSale.php" class="nav-link">
          <div class="emoji">
          💰</div>
          <span class="link-text">Sales</span>
        </a>
        </li>
      </ul>
    </nav>
    <main>
		<!-- Script 7.1 - header.html -->
		<!-- Start of page-specific content. -->