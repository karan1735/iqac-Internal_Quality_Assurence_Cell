<?php
// Include database connection code
include("php/config.php");

// Assuming $con is your database connection

// Get user ID from the request
$user_id = $_GET['user_id'];

// Prepare SQL query to fetch data
$query = "SELECT i.indicator_id, i.indicator_name,i.ref_id, m.measurement_name,m.reference_id, d.*,i.criterion_id
          FROM indicators i
          LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
          LEFT JOIN criteria c ON c.criterion_id = i.criterion_id
          LEFT JOIN data d ON m.measurement_id = d.measurement_id AND d.user_id = ?
          WHERE d.satisfied = 'no'";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = array();

// Fetch data rows
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
