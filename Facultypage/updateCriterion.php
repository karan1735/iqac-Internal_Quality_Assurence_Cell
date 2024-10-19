<?php
session_start();
include("php/config.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if criterion ID and criterion name are set in the POST data
    if (isset($_POST['criterion_id']) && isset($_POST['criterion_name'])) {
        // Sanitize input to prevent SQL injection
        $criterion_id = mysqli_real_escape_string($con, $_POST['criterion_id']);
        $criterion_name = mysqli_real_escape_string($con, $_POST['criterion_name']);

        // Update the criterion name in the database
        $update_query = "UPDATE criteria SET criterion_name = '$criterion_name' WHERE criterion_id = $criterion_id";

        if (mysqli_query($con, $update_query)) {
            // If update successful, send a success response
            echo "Criterion updated successfully";
        } else {
            // If update failed, send an error response
            echo "Error updating3 criterion: " . mysqli_error($con);
        }
    } else {
        // If criterion ID or criterion name is not set, send an error response
        echo "Criterion ID or criterion name not provided";
    }
} else {
    // If the request method is not POST, send an error response
    echo "Invalid request method";
}
?>
