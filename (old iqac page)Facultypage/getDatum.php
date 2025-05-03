<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: ./index.php");
    exit;
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    
    // Fetch data based on the selected type
    $query = "SELECT * FROM $type"; // Assuming 'criteria' and 'indicators' are the table names
    $result = mysqli_query($con, $query);

    if ($result) {
        // Fetch and return data as JSON
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Type not specified.";
}
?>
