<?php
// Include your database connection file
include("php/config.php");

// Assuming user_id is sent as a GET parameter
$userId = $_GET['user_id'];

// Query to fetch satisfaction count
$satisfactionQuery = "SELECT COUNT(*) AS satisfaction_count
FROM data d
WHERE d.satisfied = 'yes' AND d.user_id = ?";
$satisfactionStmt = mysqli_prepare($con, $satisfactionQuery);
mysqli_stmt_bind_param($satisfactionStmt, "i", $userId);
mysqli_stmt_execute($satisfactionStmt);
mysqli_stmt_store_result($satisfactionStmt);
mysqli_stmt_bind_result($satisfactionStmt, $satisfactionCount);
mysqli_stmt_fetch($satisfactionStmt);

// Query to fetch completion count
$completionQuery = "SELECT COUNT(actual) AS not_filled FROM data WHERE user_id = ? AND actual IS NOT NULL and actual <> 0";


$completionStmt = mysqli_prepare($con, $completionQuery);
mysqli_stmt_bind_param($completionStmt, "i", $userId);
mysqli_stmt_execute($completionStmt);
mysqli_stmt_store_result($completionStmt);
mysqli_stmt_bind_result($completionStmt, $not_filled_count);
mysqli_stmt_fetch($completionStmt);

// Calculate remaining count (Total count is assumed to be 91)
$totalCount = 91;
$completedCount = $not_filled_count;

// Return the counts in JSON format
echo json_encode(array(
    'satisfactionCount' => $satisfactionCount,
    'unsatisfactionCount' => $totalCount - $satisfactionCount,
    'completedCount' => $completedCount,
    'remainingCount' => $totalCount - $completedCount
));

// Close prepared statements
mysqli_stmt_close($satisfactionStmt);
mysqli_stmt_close($completionStmt);
mysqli_close($con);
?>
