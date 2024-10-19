<?php
session_start(); // Start the session
include("php/config.php");

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Check if the criterion_id parameter is set and not empty
    if (isset($_GET['criterion_id']) && !empty($_GET['criterion_id'])) {
        // Get the criterion ID from the GET parameter
        $criterionId = $_GET['criterion_id'];

        // Check if the search term parameter is set and not empty
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            // Get the search term from the GET parameter
            $searchTerm = '%' . $_GET['search'] . '%'; // Add wildcard characters for SQL LIKE query

            // Prepare and execute the query to fetch filtered data based on criterion and search term
            $query = "SELECT i.indicator_id, i.indicator_name,i.ref_id, m.measurement_name,m.reference_id, d.*,m.measurement_id
                      FROM indicators i
                      LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
                      LEFT JOIN data d ON i.indicator_id = d.measurement_id
                      WHERE i.criterion_id = ? AND d.user_id = ? AND (i.indicator_name LIKE ? OR m.measurement_name LIKE ?)";
                      

            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "iiss", $criterionId, $userId, $searchTerm, $searchTerm);
        } else {
            // Prepare and execute the query to fetch data based on criterion only
            $query = "SELECT i.indicator_id, i.indicator_name,i.ref_id, m.measurement_name,m.reference_id, d.* ,i.criterion_id,m.measurement_id
                      FROM indicators i
                      LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
                      LEFT JOIN data d ON i.indicator_id = d.measurement_id
                      WHERE i.criterion_id = ? AND d.user_id = ?";

            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ii", $criterionId, $userId);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch filtered data into an array
        $indicators = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $indicators[] = $row;
        }

        // Return the filtered indicators as JSON
        echo json_encode($indicators);
    } else {
        // Return an empty JSON array if criterion_id parameter is not set or empty
        echo json_encode([]);
    }
} else {
    // Return an empty JSON array if the user is not logged in
    echo json_encode([]);
}
?>