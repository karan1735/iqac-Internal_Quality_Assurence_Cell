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
    $getuser2 = mysqli_query($con, "SELECT * FROM users WHERE department_id = $res_departmentid AND user_type='Staff' ");
    if ($re2 = mysqli_fetch_assoc($getuser2)) {
        $res_final = $re2['user_id'];
    }
    $newuserid = $res_final;
    $satisfactioncount = "SELECT COUNT(*) AS satisfaction_count
    FROM data d
    WHERE d.satisfied = 'yes' AND d.user_id = ?";

    $satcou = mysqli_prepare($con, $satisfactioncount);
    mysqli_stmt_bind_param($satcou, "i", $newuserid); // Assuming $newuserid contains the user ID
    mysqli_stmt_execute($satcou);
    mysqli_stmt_store_result($satcou); // Store the result
    mysqli_stmt_bind_result($satcou, $satisfaction_count); // Bind the result variable

    // Fetch the result
    mysqli_stmt_fetch($satcou);


    $com = mysqli_query($con, "SELECT COUNT(actual) AS not_filled FROM data WHERE user_id = '$newuserid' AND actual IS NOT NULL AND actual <> 0");

    if ($com) {
        // Check if any rows were returned
        if (mysqli_num_rows($com) > 0) {
            // Fetch the count from the result set
            $not_filled_result = mysqli_fetch_assoc($com);
            $not_filled_count = $not_filled_result['not_filled'];
        } else {
            // No rows returned, set count to 0
            $not_filled_count = 0;
        }
    } else {
        // Handle query execution error
        echo "Error: " . mysqli_error($con);
        exit;
    }

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

    // Fetch unique criteria options from the database
    $query2 = "SELECT DISTINCT criterion_id, criterion_name FROM criteria";
    $resultCriteria = mysqli_query($con, $query2);

    // Store fetched criteria options in an array
    $criteriaOptions = array();
    while ($row = mysqli_fetch_assoc($resultCriteria)) {
        $criteriaOptions[$row['criterion_id']] = $row['criterion_name'];
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
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
            <button class="reportgenerate" onclick="redirectToReportPage()">Report</button>
            <button class="logoutbutton" onclick="createpdf()">PDF</button>

            <button class="logoutbutton" onclick="logout()">Logout</button>
        </div>
    </div>
    <br>
    <main>
    <div class="undernav">
            <div class="selection">
                <div class="dropbox">
                    <label for="criteria-select">Select Criteria:</label>
                    <select id="criteria-select" class="criteria-select">
                        <option value="">-- Select Criteria --</option>
                        <?php foreach ($criteriaOptions as $criterion_id => $criterion_name) { ?>
                            <option value="<?php echo $criterion_id; ?>"><?php echo $criterion_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="search-box">
                    <label for="search-input">Search among Criteria:</label>
                    <input type="text" id="search-input" placeholder="Search...">
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
            
            <script>
                // Get the completion count from PHP
                var completedCount = <?php echo $not_filled_count; ?>;
                var remainingCount = 91 - completedCount;
                var satisfactionCount = <?php echo $satisfaction_count; ?>;
                var unsatisfactionCount = 91 - satisfactionCount;

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

                // Get the canvas element
                var ctx1 = document.getElementById('satisfactionChart').getContext('2d');

                var ctx2 = document.getElementById('completionChart').getContext('2d');

                // Create the pie chart
                var satisfactionChart = new Chart(ctx1, {
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

                var completionChart = new Chart(ctx2, {
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
            </script>

        </div>
        <br><br>
        <table border="1" id="criteria-table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Indicator</th>
                    <th>Measurement</th>
                    <th>Acceptance level</th>
                    <th>Expected</th>
                    <th>Set Expected</th>
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
            const criteriaSelect = document.getElementById('criteria-select');
            const tableBody = document.querySelector('#criteria-table tbody');

            // Event listener for criteria dropdown change
            criteriaSelect.addEventListener('change', function() {
                const selectedCriterionId = this.value;
                // Fetch data based on selected criterion and update table
                fetchDataAndPopulateTable(selectedCriterionId);
            });

            // JavaScript function to update the actual value in the database
            function saveData(dataId) {
            // Get the input element corresponding to the expected value
            const expectedInput = document.querySelector(`input[data-row-id="${dataId}"][data-input-type="expected"]`);
            // Get the input element corresponding to the actual value
            const actualInput = document.querySelector(`input[data-row-id="${dataId}"][data-input-type="actual"]`);
            // Get the expected value
            const newValue1 = expectedInput.value;
            const newValue2 = actualInput.value;
            // Send an AJAX request to update the actual value in the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'approve.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Actual value updated successfully');
                        // Display a success message
                        alert('Data added successfully!');
                    } else {
                        console.error('Failed to update actual value');
                    }
                }
                };
                xhr.send(`data_id=${dataId}&expected=${newValue1}&actual=${newValue2}`);
            }
            // JavaScript function to update the expected value in the database
            function saveExpected(dataId) {
                // Get the input element corresponding to the expected value
                const expectedInput = document.querySelector(`input[data-row-id="${dataId}"][data-input-type="expected"]`);
                // Get the expected value
                const newValue = expectedInput.value;
                // Send an AJAX request to update the expected value in the database
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'set_expected.php', true); // Adjust the URL accordingly
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log('Expected value saved successfully');
                            // Display a success message
                            alert('Expected value saved successfully!');
                        } else {
                            console.error('Failed to save expected value');
                        }
                    }
                };
                xhr.send(`data_id=${dataId}&expected=${newValue}`);
            }


            

            function fetchDataAndPopulateTable(criterionId) {
                tableBody.innerHTML = '';

                // Fetch data for the selected criterion from the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getdataforhod.php?criterion_id=${criterionId}`, true);
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
                                    <td><button class="btn-save" onclick="saveExpected(${row.data_id})">Set</button></td>
                                    <td><input type="number" class="actual-input" data-row-id="${row.data_id}" data-input-type="actual" value="${row.actual}"></td>                                    
                                    <td>${row.percentage}</td>
                                    <td>${row.deviation}</td>
                                    <td style="text-align: center;">${row.approved ? '<span style="color: green; ">&#10004;</span>' : '<span style="color: red;">&#10008;</span>'}</td>
                                    <td><button class="btn-save" onclick="saveData(${row.data_id})"> ${row.approved ? 'Unapprove' : 'Approve'}</button></td>
                                    

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
            function redirectToReportPage() {
                // Redirect to the report page
                window.location.href = "report.php";
            }
            function logout() {
                // Redirect to the report page
                window.location.href = "logout.php";
            }
            function createpdf(){
                window.location.href = "createpdfforhod.php";
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
