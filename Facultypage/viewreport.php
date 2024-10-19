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
    // Fetch user data
    if ($result = mysqli_fetch_assoc($query)) {
        $res_Uname = $result['username'];
    }

    $query3 = "SELECT DISTINCT department_id, department_name FROM departments";
    $resultDepartments = mysqli_query($con, $query3);

    // Store fetched department options in an array
    $departmentOptions = array();
    while ($row = mysqli_fetch_assoc($resultDepartments)) {
        $departmentOptions[$row['department_id']] = $row['department_name'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            <button class="logoutbutton" onclick="data()" >Data</button>
            <button class="logoutbutton" onclick="createpdf()" >PDF</button>
            <button class="logoutbutton" onclick="analysis()" >Analysis</button>
            <button class="logoutbutton" onclick="gohome()" >Go Home</button>
            <button class="logoutbutton" onclick="logout()" >Logout</button>
            
        </div>
    </div>
    <br>


    <main>
        <h2 style="margin:10px 5px 10px 5px">&ensp;Non Conformities Report</h2>

        <div class="undernav_report">
                <div class="topic">
                    <h3>Selection</h3>
                </div>
                <div class="content">
                        <label for="department-select">Select Department:</label>
                        <select id="department-select" class="department-select">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departmentOptions as $department_id => $department_name) { ?>
                                <option value="<?php echo $department_id; ?>"><?php echo $department_name; ?></option>
                            <?php } ?>
                        </select>
                </div>                
        </div>

        <table border="1" id="criteria-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Criteria</th>
                        <th>Indicator</th>
                        <th>Measurement</th>
                        <th>Expected</th>
                        <th>Actual</th>
                        <th>Faculty Responsible</th>
                        <th>Root Cause (Reason for deviation)</th>
                        <th>Corrective Action</th>
                        <th>Target Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be dynamically added here -->
                </tbody>
</table>

            </table>

        <!-- Create session -->
        <?php $_SESSION['card1_visited'] = true; ?>
            

        <script>
            
            // Get references to elements
            const departmentSelect = document.getElementById('department-select');
            const tableBody = document.querySelector('#criteria-table tbody');

            // Event listener for department dropdown change
            departmentSelect.addEventListener('change', function() {
                const selectedDepartmentId = this.value;
                // Fetch data based on selected department, and update table
                fetchUserId(selectedDepartmentId);
            });

            function fetchUserId(departmentId) {
                // Fetch user ID associated with the selected department from the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getUserId.php?department_id=${departmentId}`, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const userId = xhr.responseText;
                            // Update table based on the fetched user ID
                            updateTable(userId);
                        } else {
                            console.error('Failed to fetch user ID');
                        }
                    }
                };
                xhr.send();
            }

            function updateTable(userId) {
                // Clear existing table rows
                tableBody.innerHTML = '';

                // Fetch data and populate table rows based on the user ID
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getViewReportData.php?user_id=${userId}`, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            if (data.length > 0) {
                                // If data is available, populate table rows
                                data.forEach(function(row) {
                                    const newRow = document.createElement('tr');
                                    newRow.innerHTML = `
                                    <td>${row.criterion_id}.${row.ref_id}.${row.reference_id}</td>
                                        <td>${row.criterion_name}</td>
                                        <td>${row.indicator_name}</td>
                                        <td>${row.measurement_name}</td>
                                        <td>${row.expected}</td>
                                        <td>${row.actual}</td>
                                        <td>${row['Resp Faculty']}</td>
                                        <td>${row['Root cause']}</td>
                                        <td>${row['Corrective Action']}</td>
                                        <td>${row['Target Date']}</td>

                                    `;
                                    tableBody.appendChild(newRow);
                                });
                            } else {
                                // If no data is available, display a message
                                const newRow = document.createElement('tr');
                                newRow.innerHTML = '<td colspan="5">No unsatisfied criteria found.</td>';
                                tableBody.appendChild(newRow);
                            }
                        } else {
                            console.error('Failed to fetch data');
                        }
                    }
                };
                xhr.send();
            }



            function changeMar() {
            // Prompt the user to enter new marquee text
            var newMarqueeText = prompt('Enter new marquee text:');
            if (newMarqueeText !== null && newMarqueeText.trim() !== '') {
                // Update the marquee text
                document.querySelector('.marquee').textContent = newMarqueeText;
                
                // Send an AJAX request to update the database
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "updateMarquee.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Response received from the server
                        console.log(xhr.responseText);
                    }
                };
                xhr.send("marquee_text=" + encodeURIComponent(newMarqueeText));
            }
        } 
            
        function logout(){
            window.location.href = "logout.php";
        }
        function data(){
            window.location.href = "data.php";
        }
        function analysis(){
            window.location.href = "analysis.php";
        }
        function report(){
            window.location.href = "viewreport.php";
        }
        function createpdf(){
            window.location.href = "createpdf.php";
        }
        function gohome(){
            window.location.href = "dashboard_admin.php";
        }

        </script>
        <?php include 'footer.php'; ?>
    </main>
</body>

</html>
