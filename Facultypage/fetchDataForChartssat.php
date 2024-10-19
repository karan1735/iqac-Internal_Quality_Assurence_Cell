<?php
session_start();
include("php/config.php");

// Check if the user is authenticated
if (!isset($_SESSION['valid'])) {
    header("Location: ./index.php");
    exit;
}

// Check database connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch filled count for each user
$stmt = $con->prepare("SELECT user_id, SUM(satisfied = 'yes') AS satisfied_count FROM data GROUP BY user_id");
$stmt->execute();
$result = $stmt->get_result();

$stmt1 = $con->prepare("SELECT department_id, department_name FROM departments");
$stmt1->execute();
$res = $stmt1->get_result();


// Prepare data for JSON response
$labels = array();
$values = array();

$firstValue = 91; // Set the first value

if ($result->num_rows > 0 && $res->num_rows > 0) {
    // Append the first value
    $labels[] = "Total"; // Assuming the first value is for User 1
    $values[] = $firstValue;
    
    // Fetch data from both result sets and append to the arrays
    while(($row = $result->fetch_assoc()) && ($row2 = $res->fetch_assoc())) {
        $department_name = $row2['department_name']; // Fetching department name from the departments result set
        $satisfied_count = $row['satisfied_count'];
        
        // Calculate filled count (out of 91)
        $satisfied_count_out_of_91 = min($satisfied_count, 91); // Limit filled count to 91
        
        // Append to the arrays
        $labels[] = $department_name;
        $values[] = $satisfied_count_out_of_91;
    }
}

// Close statements
$stmt->close();
$stmt1->close();

// Close connection
$con->close();

// Return data as JSON
echo json_encode(array('labels' => $labels, 'values' => $values));
?>
