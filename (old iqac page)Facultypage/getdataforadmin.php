<?php
session_start(); // Start the session
include("php/config.php");

// Check if the criterion_id and department_id parameters are set and not empty
if (isset($_GET['criterion_id']) && isset($_GET['department_id']) && !empty($_GET['criterion_id']) && !empty($_GET['department_id'])) {
    
    // Get the criterion ID, department ID, and user ID from the GET parameters
    $criterionId = $_GET['criterion_id'];
    $departmentId = $_GET['department_id'];
    
    // Prepare and execute the query to fetch user ID based on department ID
    $queryuser = "SELECT user_id FROM users WHERE department_id=?";
    $stmt = mysqli_prepare($con, $queryuser);
    mysqli_stmt_bind_param($stmt, "i", $departmentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Fetch the user ID
    $row = mysqli_fetch_assoc($result);
    $userId = $row['user_id'];

    // Prepare and execute the query to fetch data for the selected criterion, department, and user
    $query = "SELECT i.indicator_id, i.indicator_name,i.ref_id, m.measurement_name,m.reference_id, d.*,i.criterion_id
              FROM indicators i
              LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
              LEFT JOIN data d ON m.measurement_id = d.measurement_id AND d.user_id = ?
              WHERE i.criterion_id = ?";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $criterionId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch data into an array
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Return the data as JSON
    echo json_encode($data);
} else {
    // Return an empty JSON array if criterion_id or department_id parameters are not set or empty
    echo json_encode([]);
}
?>
