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

    // Fetch unique criteria options from the database
    $query2 = "SELECT DISTINCT criterion_id, criterion_name FROM criteria";
    $resultCriteria = mysqli_query($con, $query2);

    // Store fetched criteria options in an array
    $criteriaOptions = array();
    while ($row = mysqli_fetch_assoc($resultCriteria)) {
        $criteriaOptions[$row['criterion_id']] = $row['criterion_name'];
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
            <button class="logoutbutton" onclick="report()" >Report</button>
            <button class="logoutbutton" onclick="analysis()" >Analysis</button>
            <button class="logoutbutton" onclick="logout()" >Logout</button>
            
        </div>
    </div>
    <br>


    <main>
        <div class="undernav">
            <div class="left-top">
                <div class="topic">
                    <h3>Selection</h3>
                </div>
                <div class="content">
                    <div class="selectadmin">
                        <label for="department-select">Select Department:</label>
                        <select id="department-select" class="department-select">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departmentOptions as $department_id => $department_name) { ?>
                                <option value="<?php echo $department_id; ?>"><?php echo $department_name; ?></option>
                            <?php } ?>
                        </select>
                        <label for="criteria-admin-select">Select Criteria:</label>
                        <select id="criteria-admin-select" class="criteria-admin-select">
                            <option value="">-- Select Criteria --</option>
                            <?php foreach ($criteriaOptions as $criterion_id => $criterion_name) { ?>
                                <option value="<?php echo $criterion_id; ?>"><?php echo $criterion_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="left-right">
                        <div class="search-box">
                            <label for="search-input">Search among Criteria:</label>
                            <input type="text" id="search-input" placeholder="Search...">
                        </div>
                    
                    </div>
                </div>
                
            </div>

            <div class="status">
                <div class="topic">
                    <h3>Report</h3>
                </div>
                <div class="status-content">
                    <div class="satisfaction">
                        Satisfaction status
                        <canvas id="satisfactionChart" width="200" height="200"></canvas>
                    </div>
                    <div class="completion">
                        Completion status
                        <canvas id="completionChart" width="200" height="200"></canvas>
                    </div>
                </div>
                    
            </div>
        </div>

        <table border="1" id="criteria-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Indicator</th>
                    <th>Measurement</th>
                    <th>Acceptance level</th>
                    <th>Expected</th>
                    <th>Actual</th>
                    <th>Percentage</th>
                    <th>Deviation</th>
                    <th>Approved</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be dynamically populated based on the selected criteria -->
            </tbody>
        </table>

        <!-- Create session -->
        <?php $_SESSION['card1_visited'] = true; ?>
            

        <script>
            
            // Get references to elements
            const criteriaSelect = document.getElementById('criteria-admin-select');
            const departmentSelect = document.getElementById('department-select');
            const tableBody = document.querySelector('#criteria-table tbody');

            // Event listener for criteria dropdown change
            criteriaSelect.addEventListener('change', function() {
                const selectedCriterionId = this.value;
                const selectedDepartmentId = departmentSelect.value;
                // Fetch data based on selected criterion and department, and update table
                fetchDataAndPopulateTable(selectedCriterionId, selectedDepartmentId);
            });

            // Event listener for department dropdown change
            departmentSelect.addEventListener('change', function() {
                const selectedCriterionId = criteriaSelect.value;
                const selectedDepartmentId = this.value;
                // Fetch data based on selected criterion and department, and update table
                fetchDataAndPopulateTable(selectedCriterionId, selectedDepartmentId);
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
                            // Fetch completion and satisfaction counts for the fetched user ID
                            fetchCompletionAndSatisfactionCounts(userId);
                        } else {
                            console.error('Failed to fetch user ID');
                        }
                    }
                };
                xhr.send();
            }


            function fetchCompletionAndSatisfactionCounts(userId) {
                // Fetch completion and satisfaction counts for the provided user ID from the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getCompletionAndSatisfactionCounts.php?user_id=${userId}`, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            // Process the response data
                            const satisfactionCount = response.satisfactionCount;
                            const unsatisfactionCount = response.unsatisfactionCount;
                            const completedCount = response.completedCount;
                            const remainingCount = response.remainingCount;
                            // Call a function to display or use the data as needed
                            displayCompletionAndSatisfactionCounts(satisfactionCount, unsatisfactionCount, completedCount, remainingCount);
                        } else {
                            console.error('Failed to fetch completion and satisfaction counts');
                        }
                    }
                };
                xhr.send();
            }

            function displayCompletionAndSatisfactionCounts(satisfactionCount, unsatisfactionCount, completedCount, remainingCount) {
                // Create data for the chart1
                var data1 = {
                    labels: ['Satisfied', 'Unsatisfied'],
                    datasets: [{
                        data: [satisfactionCount, unsatisfactionCount],
                        backgroundColor: ['#36A2EB', '#FF6384'],
                        hoverBackgroundColor: ['#36A2EB', '#FF6384']
                    }]
                };

                // Create data for the chart2
                var data2 = {
                    labels: ['Completed', 'Remaining'],
                    datasets: [{
                        data: [completedCount, remainingCount],
                        backgroundColor: ['#36A2EB', '#FF6384'],
                        hoverBackgroundColor: ['#36A2EB', '#FF6384']
                    }]
                };

                // Get the canvas elements
                var ctx1 = document.getElementById('satisfactionChart').getContext('2d');
                var ctx2 = document.getElementById('completionChart').getContext('2d');

                // Update or create the pie charts
                if (window.satisfactionChart instanceof Chart) {
                    // If chart exists, update data
                    window.satisfactionChart.data = data1;
                    window.satisfactionChart.update();
                } else {
                    // Create new chart if not exist
                    window.satisfactionChart = new Chart(ctx1, {
                        type: 'pie',
                        data: data1,
                        options: {
                            responsive: true,
                            plugins: {
                                datalabels: {
                                    color: '#fff', // Label text color
                                    formatter: function(value, context) {
                                        return value; // Display the value
                                    }
                                }
                            }
                        }
                    });
                }

                if (window.completionChart instanceof Chart) {
                    // If chart exists, update data
                    window.completionChart.data = data2;
                    window.completionChart.update();
                } else {
                    // Create new chart if not exist
                    window.completionChart = new Chart(ctx2, {
                        type: 'pie',
                        data: data2,
                        options: {
                            responsive: true,
                            plugins: {
                                datalabels: {
                                    color: '#fff', // Label text color
                                    formatter: function(value, context) {
                                        return value; // Display the value
                                    }
                                }
                            }
                        }
                    });
                }
            }



            // JavaScript function to update the actual value in the database
            function saveData(dataId) {
                // Get the input element corresponding to the expected value
                const expectedInput = document.querySelector(`input[data-row-id="${dataId}"][data-input-type="expected"]`);
                // Get the input element corresponding to the actual value
                const actualInput = document.querySelector(`input[data-row-id="${dataId}"][data-input-type="actual"]`);
                // Get the expected value
                const newValue1 = expectedInput.value;
                const newValue2 = actualInput.value;

                // Send an AJAX request to update the expected value in the database
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'saveExpectedData.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log('Expected value updated successfully');
                            // Display a success message
                            alert('Data added successfully!');
                        } else {
                            console.error('Failed to update expected value');
                        }
                    }
                };
                xhr.send(`data_id=${dataId}&expected=${newValue1}&actual=${newValue2}`);
            }


            

            function fetchDataAndPopulateTable(criterionId, departmentId) {
                tableBody.innerHTML = '';

                // Fetch data for the selected criterion and department from the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getdataforadmin.php?criterion_id=${criterionId}&department_id=${departmentId}`, true);
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            // Populate the table with the fetched data
                            data.forEach(row => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${row.criterion_id}.${row.ref_id}.${row.reference_id}</td>
                                    <td>${row.indicator_name}</td>
                                    <td>${row.measurement_name}</td>
                                    <td>${row.acceptance}</td>
                                    <td><input type="number" class="actual-input" data-row-id="${row.data_id}" data-input-type="expected" value="${row.expected}"></td>
                                    <td><input type="number" class="actual-input" data-row-id="${row.data_id}" data-input-type="actual" value="${row.actual}"></td>
                                    <td>${row.percentage}</td>
                                    <td>${row.deviation}</td>
                                    <td style="text-align: center;">${row.approved ? '<span style="color: green; ">&#10004;</span>' : '<span style="color: red;">&#10008;</span>'}</td>
                                    <td><button class="btn-save" onclick="saveData(${row.data_id})">Update</button></td>
                                `;
                                tableBody.appendChild(tr);
                            });

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
        function analysis(){
            window.location.href = "analysis.php";
        }
        function report(){
            window.location.href = "viewreport.php";
        }
        function createpdf(){
            window.location.href = "createpdf.php";
        }
        function data(){
            window.location.href = "data.php";
        }
        // Get reference to the search input field
        const searchInput = document.getElementById('search-input');

        // Event listener for search input
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let rowMatches = false;
                cells.forEach(cell => {
                    const cellText = cell.textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        rowMatches = true;
                    }
                });
                row.style.display = rowMatches ? '' : 'none';
            });
        });

        </script>
        <?php include 'footer.php'; ?>
    </main>
</body>

</html>
