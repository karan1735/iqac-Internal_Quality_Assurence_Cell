<?php
include("php/config.php");

// Define an array to store formulas for each measurement ID
$formulas = array(
    1 => "actual / expected * 100",
    2 => "actual / expected * 100",
    3 => "actual / ( 0.7 * expected ) * 100",
    4 => "actual / expected * 100",
    5 => "actual / ( 0.03 * expected ) * 100",
    6 => "actual / ( 0.03 * expected ) * 100",
    7 => "actual / expected * 100",
    8 => "actual / ( 0.2 * expected ) * 100",
    9 => "actual / expected * 100",
    10 => "actual / ( 0.1 * expected ) * 100",
    11 => "actual / expected * 100",
    12 => "actual / expected * 100",
    13 => "actual / expected * 100",
    14 => "actual / ( 2 * expected ) * 100",
    15 => "actual / ( 0.2 * expected ) * 100",
    16 => "actual / expected * 100",
    17 => "actual / expected * 100",
    18 => "actual / expected * 100",
    19 => "actual / expected * 100",
    20 => "actual / expected * 100",
    21 => "actual / expected * 100",
    22 => "actual / expected * 100",
    23 => "actual / expected * 100",
    24 => "actual / expected * 100",
    25 => "actual / expected * 100",
    26 => "actual / expected * 100",
    27 => "actual / expected * 100",
    28 => "actual / expected * 100",
    29 => "actual / expected * 100",
    30 => "actual / expected * 100",
    31 => "actual / expected * 100",
    32 => "actual / expected * 100",
    33 => "actual / expected * 100",
    34 => "actual / expected * 100",
    35 => "actual / expected * 100",
    36 => "actual / expected * 100",
    37 => "actual / expected * 100",
    38 => "actual / expected * 100",
    39 => "actual / expected * 100",
    40 => "actual / expected * 100",
    41 => "actual / expected * 100",
    42 => "actual / expected * 100",
    43 => "actual / expected * 100",
    44 => "actual / expected * 100",
    45 => "actual / expected * 100",
    46 => "actual / expected * 100",
    47 => "actual / expected * 100",
    48 => "actual / expected * 100",
    49 => "actual / expected * 100",
    50 => "actual / expected * 100",
    51 => "actual / expected * 100",
    52 => "actual / expected * 100",
    53 => "actual / expected * 100",
    54 => "actual / expected * 100",
    55 => "actual / expected * 100",
    56 => "actual / expected * 100",
    57 => "actual / expected * 100",
    58 => "actual / expected * 100",
    59 => "actual / expected * 100",
    60 => "actual / expected * 100",
    61 => "actual / expected * 100",
    62 => "actual / expected * 100",
    63 => "actual / expected * 100",
    64 => "actual / expected * 100",
    65 => "actual / expected * 100",
    66 => "actual / expected * 100",
    67 => "actual / expected * 100",
    68 => "actual / expected * 100",
    69 => "actual / expected * 100",
    70 => "actual / expected * 100",
    71 => "actual / expected * 100",
    72 => "actual / expected * 100",
    73 => "actual / expected * 100",
    74 => "actual / expected * 100",
    75 => "actual / expected * 100",
    76 => "actual / expected * 100",
    77 => "actual / expected * 100",
    78 => "actual / expected * 100",
    79 => "actual / expected * 100",
    80 => "actual / expected * 100",
    81 => "actual / expected * 100",
    82 => "actual / expected * 100",
    83 => "actual / expected * 100",
    84 => "actual / expected * 100",
    85 => "actual / expected * 100",
    86 => "actual / expected * 100",
    87 => "actual / expected * 100",
    88 => "actual / expected * 100",
    89 => "actual / expected * 100",
    90 => "actual / expected * 100",
    91 => "actual / expected * 100",
);

// Check if data_id and actual_value parameters are set
if (isset($_POST['data_id']) && isset($_POST['actual'])) {
    // Sanitize the input
    $dataId = mysqli_real_escape_string($con, $_POST['data_id']);
    $actual = mysqli_real_escape_string($con, $_POST['actual']);

    // Prepare and execute the query to update the actual value
    $queryUpdateActual = "UPDATE data SET actual = $actual WHERE data_id = $dataId";
    $resultUpdateActual = mysqli_query($con, $queryUpdateActual);

    $getMesId = "SELECT measurement_id FROM data WHERE data_id = $dataId";
    $MesId = mysqli_query($con, $getMesId);
    $mes = mysqli_fetch_assoc($MesId);
    $measurement_id = $mes['measurement_id'];


    if ($resultUpdateActual) {
        // Query to fetch expected value based on data_id
        $queryFetchExpected = "SELECT expected FROM data WHERE data_id = $dataId";
        $resultFetchExpected = mysqli_query($con, $queryFetchExpected);

        if ($resultFetchExpected && mysqli_num_rows($resultFetchExpected) > 0) {
            $row = mysqli_fetch_assoc($resultFetchExpected);
            $expected = $row['expected'];

            // Check if the measurement_id exists in the formulas array
            if (array_key_exists($measurement_id, $formulas)) {
                // Get the formula from the formulas array
                $formula = $formulas[$measurement_id];
                // Example: $formula = "actual / expected * 100";

                // Replace variables actual and expected into the formula
                $formula = str_replace("actual", $actual, $formula);
                $formula = str_replace("expected", $expected, $formula);

                // Evaluate the formula
                $evaluatedValue = eval("return ($formula);");

                // Round off to two decimal places
                $evaluatedValue = round($evaluatedValue, 2);

                if($evaluatedValue>100){
                    $evaluatedValue = 100;
                }
                else if($evaluatedValue<0){
                    $evaluatedValue = 0;
                }

                // Prepare and execute the query to update the percentage record
                $queryPercentage = "UPDATE data SET percentage = $evaluatedValue WHERE data_id = $dataId";
                $resultPercentage = mysqli_query($con, $queryPercentage);

                if ($resultPercentage) {
                    $deviation = 100 - $evaluatedValue;
                    $deviation = round($deviation, 2);

                    if($deviation>100){
                        $deviation=100;
                    }
                    else if($deviation<0){
                        $deviation=0;
                    }

                    $queryDeviation = "UPDATE data SET deviation = $deviation WHERE data_id = $dataId";
                    $resultDeviation = mysqli_query($con, $queryDeviation);

                    $querySetUnapprove= "UPDATE data SET approved = 0 WHERE data_id = $dataId";
                    $resultSetUnapprove = mysqli_query($con, $querySetUnapprove);
            
                    if ($resultDeviation) {
                        if($deviation>0){
                                $querySetSatisfied = "UPDATE data SET satisfied = 'NO' WHERE data_id = $dataId";
                                $resultSetSatisfied = mysqli_query($con, $querySetSatisfied);
                                if (!$resultSetSatisfied) {
                                    // Return an error response if failed to set satisfied to 1
                                    http_response_code(500);
                                    echo "Failed to set satisfied to 1";
                                    exit;
                                }
                        }else{
                            $querySetSatisfied = "UPDATE data SET satisfied = 'YES' WHERE data_id = $dataId";
                                $resultSetSatisfied = mysqli_query($con, $querySetSatisfied);
                                if (!$resultSetSatisfied) {
                                    // Return an error response if failed to set satisfied to 1
                                    http_response_code(500);
                                    echo "Failed to set satisfied to 0";
                                    exit;
                                }
                        }
                        // Return a success response
                        http_response_code(200);
                        echo "Percentage and deviation records updated successfully";
                    } else {
                        // Return an error response
                        http_response_code(500);
                        echo "Failed to update deviation record";
                    }
                } else {
                    // Return an error response
                    http_response_code(500);
                    echo "Failed to update percentage record";
                }
            } else {
                // Return an error response if formula not found for measurement_id
                http_response_code(500);
                echo "Formula not found for measurement_id: $dataId";
            }
        } else {
            // Return an error response if expected value not found
            http_response_code(500);
            echo "Expected value not found for data_id: $dataId";
        }
    } else {

        // Return an error response if failed to update actual value
        http_response_code(500);
        echo "Failed to update actual value";
    }
} else {
    // Return a bad request response if data_id or actual_value parameters are not set
    http_response_code(400);
    echo "Bad request";
}
?>
