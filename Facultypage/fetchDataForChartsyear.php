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

// Fetch data for the last 3 years for each department
$stmt = $con->prepare("SELECT user_id, YEAR(year) AS year, SUM(satisfied) AS satisfied_count FROM data WHERE YEAR(year) >= YEAR(CURDATE()) - 3 GROUP BY user_id, year");
$stmt->execute();
$result = $stmt->get_result();

$stmt1 = $con->prepare("SELECT department_id, department_name FROM departments");
$stmt1->execute();
$res = $stmt1->get_result();

// Prepare data for JSON response
$labels = array();
$data = array();

while($row = $result->fetch_assoc()) {
    $department_name = $row['department_name'];
    $year = $row['year'];
    $satisfied_count = $row['satisfied_count'];

    // Append to the data array
    if (!isset($data[$department_name])) {
        $data[$department_name] = array();
    }

    // Store satisfied count for each year
    $data[$department_name][$year] = $satisfied_count;
}

// Generate labels for the last 3 years
$current_year = date("Y");
for ($i = $current_year - 2; $i <= $current_year; $i++) {
    $labels[] = $i;
}

// Close statements
$stmt->close();
$stmt1->close();
// Close connection
$con->close();

// Return data as JSON
echo json_encode(array('labels' => $labels, 'data' => $data));
?>
