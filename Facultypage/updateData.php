<?php
// Include the database connection file
include("php/config.php");

// Check if the necessary parameters are set
if (isset($_POST['data_id']) && isset($_POST['indicator']) && isset($_POST['measurement']) && isset($_POST['acceptance']) && isset($_POST['indicator_id']) && isset($_POST['measurement_id']) && isset($_POST['prev_acceptance'])) {
    // Sanitize the input data to prevent SQL injection
    $data_id = mysqli_real_escape_string($con, $_POST['data_id']);
    $indicator_id = mysqli_real_escape_string($con, $_POST['indicator_id']);
    $measurement_id = mysqli_real_escape_string($con, $_POST['measurement_id']);
    $indicator = mysqli_real_escape_string($con, $_POST['indicator']);
    $measurement = mysqli_real_escape_string($con, $_POST['measurement']);
    $acceptance = mysqli_real_escape_string($con, $_POST['acceptance']);
    $prev_acceptance = mysqli_real_escape_string($con, $_POST['prev_acceptance']);

    // Update the indicator name in the database
    $updateIndicatorQuery = "UPDATE indicators SET indicator_name = '$indicator' WHERE indicator_id = '$indicator_id'";
    // Update the measurement name in the database
    $updateMeasurementQuery = "UPDATE measurements SET measurement_name = '$measurement' WHERE measurement_id = '$measurement_id'";
    // Update the acceptance value in the data table for the specific measurement_id and previous acceptance value
    $updateAcceptanceQuery = "UPDATE data SET acceptance = '$acceptance' WHERE measurement_id = '$measurement_id' AND acceptance = '$prev_acceptance'";

    // Execute all queries within a transaction to ensure atomicity
    mysqli_begin_transaction($con);
    $success = true;
    if (!mysqli_query($con, $updateIndicatorQuery) || !mysqli_query($con, $updateMeasurementQuery) || !mysqli_query($con, $updateAcceptanceQuery)) {
        $success = false;
    }

    if ($success) {
        // If all updates are successful, commit the transaction
        mysqli_commit($con);
        // Return a success message
        echo "Data updated successfully!";
    } else {
        // If any update fails, rollback the transaction and return an error message
        mysqli_rollback($con);
        echo "Error updating data: " . mysqli_error($con);
    }
} else {
    // If the necessary parameters are not set, return an error message
    echo "Error: Missing parameters";
}
?>
