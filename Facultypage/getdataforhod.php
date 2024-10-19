<?php
session_start(); // Start the session
include("php/config.php");

// Check if the criterion_id parameter is set and not empty
if (isset($_GET['criterion_id']) && !empty($_GET['criterion_id'])) {
    // Get the criterion ID from the GET parameter

    
    $criterionId = $_GET['criterion_id'];

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {

        $id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $getuser = mysqli_query($con, "SELECT * FROM users WHERE user_id = $id");
        // Fetch user data
        if ($re = mysqli_fetch_assoc($getuser)) {
            $res_departmentid = $re['department_id'];
        }
        $getuser2 = mysqli_query($con, "SELECT * FROM users WHERE department_id = $res_departmentid AND user_type='Staff'");
        if ($re2 = mysqli_fetch_assoc($getuser2)) {
            $res_final = $re2['user_id'];
        }
        $newuserid = $res_final;

        // Prepare and execute the query to fetch all indicators and measurements for the selected criterion and user
        $query = "SELECT i.indicator_id, i.indicator_name,i.ref_id, m.measurement_name,m.reference_id, d.*,i.criterion_id
                  FROM indicators i
                  LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
                  LEFT JOIN data d ON i.indicator_id = d.measurement_id
                  WHERE i.criterion_id = ? AND d.user_id = ?";


        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ii", $criterionId, $newuserid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch data into an array
        $indicators = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $indicators[] = $row;
        }

        // Return the indicators as JSON
        echo json_encode($indicators);
    } else {
        // Return an empty JSON array if the user is not logged in
        echo json_encode([]);
    }
} else {
    // Return an empty JSON array if criterion_id parameter is not set or empty
    echo json_encode([]);
}
?>