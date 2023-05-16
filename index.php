<?php # Script 3.4 - index.php
$page_title = 'PHP & MySQL';
include ('./includes/header.html');
?>
<h1 id="mainhead">Using PHP and MySQL</h1>
<p>Connecting to MySQL and selecting the database</p>
<p>Executing Simple Queries.</p>
<p>Retrieving Query Results</p>
<p>Ensuring Secure SQL</p>
<p>Counting Returned Records</p>
<p>Updating Records with PHP</p>


<h2>Summary</h2>
<p>mysql_connect(): To konek to MySQL </p>
<p>mysql_select_db() : To select database </p>
<p>mysql_query(): Executing Simple Queries </p>
<p>mysql_close(): To close the existing MySQL connection </p>
<p>mysql_fetch_array(): To retrieve query results </p>
<p>mysql_assoc: associative arrays </p>
<p>mysql_num: indexed arrays </p>
<p>mysql_both: both arrays, associative and indexed arrays </p>
<p>mysql_free_result(): To free up the query result resources </p>
<p>mysql_num_rows(): To count return records </p>
<p>mysql_affected_rows() returns the number of rows affected by an INSERT, UPDATE or DELETE query. </p>

<?php
include ('./includes/footer.html');
?>
