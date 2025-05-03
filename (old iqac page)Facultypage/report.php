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

    $query = mysqli_query($con, "SELECT * FROM users WHERE user_id = $id");
    
    // Fetch user data
    if ($result = mysqli_fetch_assoc($query)) {
        $res_Uname = $result['username'];
    }
    $marquee_query = mysqli_query($con, "SELECT marquee FROM information");

    if ($marquee_query) {
        // Check if any rows were returned
        if (mysqli_num_rows($marquee_query) > 0) {
            // Fetch the marquee text from the first row
            $info_marquee = mysqli_fetch_assoc($marquee_query);
            $marquee_text = $info_marquee['marquee'];
            // Now $marquee_text contains the marquee text
        } else {
            // No rows returned
            echo "No marquee text found";
        }
    } else {
        // Error in executing the query
        echo "Error: " . mysqli_error($con);
    }

} else {
    echo "User ID not found in session.";
    exit; // Ensure script stops executing if user ID not found
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style/card1.css">
</head>

<body>
    <div class="iqac">
        <h1>Internal Quality Assurance Cell</h1>
    </div>
    <marquee behavior="" direction="left"><?php echo $marquee_text; ?></marquee>

    <div class="nav">
        <div class="logo">
            <p>KEC</p>
        </div>
        <div class="right-links">            
        <p style="text-transform: capitalize;">Welcome, <?php echo $res_Uname; ?></p>
            <button class="backhome" onclick="backhome()" >Go home</button>
            <button class="logoutbutton" onclick="logout()">Logout</button>

        </div>
    </div>
    <br>
    <div class="report">

        <h2> Non Conformities Report</h2>
        <br>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table border="1">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Criteria</th>
                        <th>Indicator</th>
                        <th>Measurement</th>
                        <th>Expected</th>
                        <th>Actual</th>
                        <th>Responsible Faculty</th>
                        <th>Root Cause (Reason for deviation) </th>
                        <th>Corrective Action</th>
                        <th>Target Date</th>
                        <th>Submit</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    // Fetch data and populate table rows
                    $query = "SELECT i.indicator_id, i.indicator_name,i.ref_id,m.reference_id,c.criterion_id, c.criterion_name, m.measurement_name, d.*
                    FROM indicators i
                    LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
                    LEFT JOIN criteria c ON c.criterion_id = i.criterion_id
                    LEFT JOIN data d ON m.measurement_id = d.measurement_id AND d.user_id = ?
                    WHERE d.satisfied = 'no'";

                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "i", $newuserid);
                    mysqli_stmt_execute($stmt);
                    $report_query = mysqli_stmt_get_result($stmt);

                    if ($report_query && mysqli_num_rows($report_query) > 0) {
                        while ($row = mysqli_fetch_assoc($report_query)) {
                            ?>
                            <tr>
                                <td><?php echo $row['criterion_id'] . '.' . $row['ref_id'] . '.' . $row['reference_id']; ?></td>
                                <td><?php echo $row['criterion_name']; ?></td>
                                <td><?php echo $row['indicator_name']; ?></td>
                                <td><?php echo $row['measurement_name']; ?></td>
                                <td><?php echo $row['expected']; ?></td>
                                <td><?php echo $row['actual']; ?></td>
                                <td><input type="text" id="responsible_fac_<?php echo $row['data_id']; ?>" value="<?php echo $row['Resp Faculty']; ?>"></td>
                                <td><textarea cols="30" rows="7" data-row-id="<?php echo $row['data_id']; ?>" data-input-type="cause"><?php echo $row['Root cause']; ?></textarea></td>
                                <td><textarea cols="30" rows="7 " data-row-id="<?php echo $row['data_id']; ?>" data-input-type="action"><?php echo $row['Corrective Action']; ?></textarea></td>
                                <td><textarea cols="30" rows="7 " data-row-id="<?php echo $row['data_id']; ?>" data-input-type="target"><?php echo $row['Target Date']; ?></textarea></td>
                                <td><button type="button" id="btn-save" class="btn-save" onclick="saveData(<?php echo $row['data_id']; ?>)">Submit</button></td>    
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No unsatisfied criteria found.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
    <script>
        function saveData(dataId) {
            // Get the textarea elements corresponding to the fields
            const rootcause = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="cause"]`);
            const correctiveaction = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="action"]`);
            const targetdate = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="target"]`);
            const respFaculty = document.querySelector(`#responsible_fac_${dataId}`);

            // Get the values
            const newValue1 = rootcause.value;
            const newValue2 = correctiveaction.value;
            const newValue3 = targetdate.value;
            const newValue4 = respFaculty.value;

            // Send an AJAX request to update the values in the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'saveReportData.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Report updated successfully');
                        // Display a success message
                        alert('Report updated successfully!');
                    } else {
                        console.error('Failed to update report');
                    }
                }
            };
            xhr.send(`data_id=${dataId}&rootcause=${newValue1}&correctiveaction=${newValue2}&targetdate=${newValue3}&responsible_fac=${newValue4}`);
        }

        function backhome() {
            window.location.href = "dashboard_hod.php";
        }

        function logout() {
            window.location.href = "logout.php";
        }
    </script>
    
    <?php include 'footer.php'; ?>

</body>

</html>
