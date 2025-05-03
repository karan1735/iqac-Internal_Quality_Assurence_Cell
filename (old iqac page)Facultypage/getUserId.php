<?php
// Include your database connection file
include("php/config.php");

// Assuming department_id is sent as a GET parameter
$departmentId = $_GET['department_id'];

// Query to fetch user ID associated with the selected department
$userQuery = "SELECT user_id FROM users WHERE department_id = ? AND user_type='Staff'";
$stmt = mysqli_prepare($con, $userQuery);
mysqli_stmt_bind_param($stmt, "i", $departmentId);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt); // Store the result
mysqli_stmt_bind_result($stmt, $userId); // Bind the result variable

// Fetch the result
mysqli_stmt_fetch($stmt);

// Return the user ID
echo $userId;
?>
