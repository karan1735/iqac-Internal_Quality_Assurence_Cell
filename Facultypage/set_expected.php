<?php
include("php/config.php");

// Check if data_id and expected parameters are set
if (isset($_POST['data_id'], $_POST['expected'])) {
    // Sanitize the input
    $dataId = mysqli_real_escape_string($con, $_POST['data_id']);
    $expected = mysqli_real_escape_string($con, $_POST['expected']);

    // Prepare and execute the query to update the expected value
    $queryUpdateExpected = "UPDATE data SET expected = $expected WHERE data_id = $dataId";
    $resultUpdateExpected = mysqli_query($con, $queryUpdateExpected);

    if ($resultUpdateExpected) {
        // Return a success response
        http_response_code(200);
        echo "Expected value updated successfully";
    } else {
        // Return an error response
        http_response_code(500);
        echo "Failed to update expected value";
    }
} else {
    // Return a bad request response if data_id or expected parameters are not set
    http_response_code(400);
    echo "Bad request";
}
?>
