<?php
session_start();
include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: ./index.php");
    exit;
}

$res_Uname = "";

$id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($id) {
    $query = mysqli_query($con, "SELECT * FROM users WHERE user_id = $id");

    $marquee_query = mysqli_query($con, "SELECT marquee FROM information");

    if ($marquee_query) {
        if (mysqli_num_rows($marquee_query) > 0) {
            $info_marquee = mysqli_fetch_assoc($marquee_query);
            $marquee_text = $info_marquee['marquee'];
        } else {
            echo "No marquee text found";
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }

    if ($result = mysqli_fetch_assoc($query)) {
        $res_Uname = $result['username'];
    }
} else {
    echo "User ID not found in session.";
    exit; // Ensure script stops executing if user ID not found
}

// Fetch criteria
$criteria_query = mysqli_query($con, "SELECT * FROM criteria");
$criteria = array();
while ($row = mysqli_fetch_assoc($criteria_query)) {
    $criteria[] = $row;
}

// Fetch indicators
$indicators_query = mysqli_query($con, "SELECT * FROM indicators");
$indicators = array();
while ($row = mysqli_fetch_assoc($indicators_query)) {
    $indicators[] = $row;
}

// Fetch measurements
$measurements_query = mysqli_query($con, "SELECT * FROM measurements");
$measurements = array();
while ($row = mysqli_fetch_assoc($measurements_query)) {
    $measurements[$row['indicator_id']][] = $row; // Group measurements by indicator_id
}

// Fetch descriptions
$descriptions_query = mysqli_query($con, "SELECT * FROM data WHERE user_id = 2");
$descriptions = array();
while ($row = mysqli_fetch_assoc($descriptions_query)) {
    $descriptions[$row['measurement_id']] = $row; // Store descriptions by measurement_id
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style/card1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Function to fetch indicators for the selected criterion
       

        // Function to toggle visibility of indicator input field based on selection
        function toggleIndicatorInput() {
            var indicatorType = document.getElementById('indicator_type').value;
            var indicatorInput = document.getElementById('indicator_input');
            indicatorInput.style.display = (indicatorType === 'new') ? 'block' : 'none';
        }

        // Function to toggle visibility of measurement input field based on selection
        function toggleMeasurementInput() {
            var measurementType = document.getElementById('measurement_type').value;
            var measurementInput = document.getElementById('measurement_input');
            measurementInput.style.display = (measurementType === 'new') ? 'block' : 'none';
        }
    </script>
</head>

<body>
    <div class="iqac">
        <h1>Internal Quality Assurance Cell</h1>
    </div>
    <div class="mar">
        <marquee class="marquee" behavior="scroll" direction="left"><?php echo $marquee_text; ?></marquee>
    </div>
    <div class="nav">
        <div class="logo">
            <p>KEC</p>
        </div>

        <div class="right-links">
            <p style="text-transform: capitalize;">Welcome, <?php echo $res_Uname; ?></p>
            <button class="logoutbutton" onclick="changeMar()">Info</button>
            <button class="logoutbutton" onclick="data()">Data</button>
            <button class="logoutbutton" onclick="createpdf()">PDF</button>
            <button class="logoutbutton" onclick="report()">Report</button>
            <button class="logoutbutton" onclick="home()">Go Home</button>
            <button class="logoutbutton" onclick="logout()">Logout</button>

        </div>
    </div>
    <br>

    <main>
        <div class="form-container">
            <h2>Add New Data</h2>
            <form action="process_new_data.php" method="post">
                <label for="criterion">Select Existing Criterion:</label>
                <select name="criterion" id="criterion" onchange="fetchIndicators()">
    <option value="">Select Criterion</option>
    <?php
    // Fetch existing criteria from the database and populate the dropdown
    foreach ($criteria as $criterion) {
        echo "<option value='" . $criterion['criterion_id'] . "'>" . $criterion['criterion_name'] . "</option>";
    }
    ?>
</select>
                <br><br>
                <label for="new_criterion">Or Enter New Criterion:</label>
                <input type="text" name="new_criterion" id="new_criterion">
                <br><br>

                <!-- Select new or existing indicator -->
                <label for="indicator_type">Select Indicator Type:</label>
                <select name="indicator_type" id="indicator_type" onchange="toggleIndicatorInput()">
                    <option value="existing">Existing Indicator</option>
                    <option value="new">New Indicator</option>
                </select>
                <br><br>

                <!-- Input field for indicator -->
                <div id="indicator_input" style="display: none;">
                    <label for="indicator">Indicator:</label>
                    <input type="text" name="indicator" id="indicator" required>
                </div>
                <br>

                <!-- Select new or existing measurement -->
                <label for="measurement_type">Select Measurement Type:</label>
                <select name="measurement_type" id="measurement_type" onchange="toggleMeasurementInput()">
                    <option value="existing">Existing Measurement</option>
                    <option value="new">New Measurement</option>
                </select>
                <br><br>

                <!-- Input field for measurement -->
                <div id="measurement_input" style="display: none;">
                    <label for="measurement">Measurement:</label>
                    <input type="text" name="measurement" id="measurement" required>
                </div>

                <!-- Add more input fields for other data -->

                <input type="submit" value="Submit">
            </form>
        </div>

        <!-- Include footer -->
        <?php include 'footer.php'; ?>
    </main>

</body>

</html>
