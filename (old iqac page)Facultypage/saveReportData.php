<?php
include("php/config.php");

// Check if data_id, rootcause, correctiveaction, targetdate, and respfaculty parameters are set
if (isset($_POST['data_id'], $_POST['rootcause'], $_POST['correctiveaction'], $_POST['targetdate'], $_POST['responsible_fac'])) {
    // Sanitize the input
    $dataId = mysqli_real_escape_string($con, $_POST['data_id']);
    $cause = mysqli_real_escape_string($con, $_POST['rootcause']);
    $action = mysqli_real_escape_string($con, $_POST['correctiveaction']);
    $target = mysqli_real_escape_string($con, $_POST['targetdate']);
    $respFaculty = mysqli_real_escape_string($con, $_POST['responsible_fac']);

    // Prepare and execute the query to update the report
    $query = "UPDATE data SET `Root cause` = '$cause', `Corrective Action` = '$action', `Target Date`='$target', `Resp Faculty`='$respFaculty' WHERE data_id = $dataId";
    $resultUpdateReport = mysqli_query($con, $query);

    if ($resultUpdateReport) {
        // Return a success response if the report is updated successfully
        http_response_code(200);
        echo "Report updated successfully";
    } else {
        // Return an error response if failed to update the report
        http_response_code(500);
        echo "Failed to update report: " . mysqli_error($con);
    }
} else {
    // Return a bad request response if any required parameters are not set
    http_response_code(400);
    echo "Bad request";
}
?>
