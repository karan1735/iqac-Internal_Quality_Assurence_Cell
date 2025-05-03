<?php
// Establish connection to your database
include("php/config.php");

// Check if the connection was successful
if (!$con) {
    echo "Error: Unable to connect to the database. " . mysqli_connect_error();
    exit();
}

// Get the new marquee text from the POST request
$newMarqueeText = isset($_POST['marquee_text']) ? $_POST['marquee_text'] : '';

// Prepare the SQL query with a parameterized statement
$query = "UPDATE information SET marquee = ?";

// Prepare the statement
if ($stmt = mysqli_prepare($con, $query)) {
    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "s", $newMarqueeText);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Marquee text updated successfully";
    } else {
        echo "Error updating marquee text: " . mysqli_error($con);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error: Unable to prepare statement. " . mysqli_error($con);
}

// Close database connection
mysqli_close($con);
?>
