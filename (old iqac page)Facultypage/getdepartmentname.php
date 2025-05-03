<?php
include("php/config.php");

if (isset($_POST['department_id'])) {
    $selectedDepartmentId = $_POST['department_id'];

    $query = "SELECT department_name FROM departments WHERE department_id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $selectedDepartmentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $departmentName);
    mysqli_stmt_fetch($stmt);

    echo $departmentName; // Send department name back to JavaScript
} else {
    echo "Department ID not provided.";
}
?>
