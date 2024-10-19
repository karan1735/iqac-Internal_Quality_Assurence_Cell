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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        <div class="undernav_analysis">
            <div class="left-top">
                <div class="topic">
                    <h3>Analysis based on :</h3>
                </div>
                <div class="content">
                    <form id="selectionForm">
                        <input type="checkbox" id="departments" name="selection" value="departments">
                        <label for="departments">Completion</label><br>
                        <input type="checkbox" id="criteria" name="selection" value="satisfaction">
                        <label for="criteria">Satisfaction</label><br>
                        <input type="checkbox" id="unsatisfaction" name="selection" value="unsatisfaction">
                        <label for="unsatisfaction">Unsatisfaction</label><br>
                    </form>
                </div>
            </div>
        </div>

        <div class="chart-container" id="dep_chartContainer" style="display: none;">
            <h3>Completion Chart</h3>
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>

        <div class="chart-container" id="sat_chartContainer" style="display: none;">
        <h3>Satisfaction Chart</h3>
            <canvas id="myChartsat" width="400" height="400"></canvas>
        </div>

        <div class="chart-container" id="unsat_chartContainer" style="display: none;">
        <h3>Unsatisfaction Chart</h3>
            <canvas id="myChartunsat" width="400" height="400"></canvas>
        </div>

    </main>

    <script>
        document.getElementById('selectionForm').addEventListener('change', function () {
            var selectedOptions = document.querySelectorAll('input[name="selection"]:checked');
            if (selectedOptions.length > 0) {
                selectedOptions.forEach(function (option) {
                    if (option.value === 'departments') {
                        fetchDataAndRenderChartforcom('completion');
                    } else if (option.value === 'satisfaction') {
                        fetchDataAndRenderChartforsat('satisfaction');
                    } else if (option.value === 'unsatisfaction') {
                        fetchDataAndRenderChartforunsat('unsatisfaction');
                    }
                });
            } else {
                document.getElementById("dep_chartContainer").style.display = "none";
                document.getElementById("sat_chartContainer").style.display = "none";
                document.getElementById("unsat_chartContainer").style.display = "none";
            }
        });

        function fetchDataAndRenderChartforunsat(option) {
            // AJAX request to server-side script (PHP)
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetchDataForChartsunsat.php?option=' + option, true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var data = JSON.parse(xhr.responseText);
                    renderChartunsat(data);
                    document.getElementById("unsat_chartContainer").style.display = "block";
                } else {
                    console.error('Request failed');
                }
            };
            xhr.onerror = function () {
                console.error('Request failed');
            };
            xhr.send();
        }

        function fetchDataAndRenderChartforcom(option) {
            // AJAX request to server-side script (PHP)
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetchDataForCharts.php?option=' + option, true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var data = JSON.parse(xhr.responseText);
                    renderChart(data);
                    document.getElementById("dep_chartContainer").style.display = "block";
                } else {
                    console.error('Request failed');
                }
            };
            xhr.onerror = function () {
                console.error('Request failed');
            };
            xhr.send();
        }

        function fetchDataAndRenderChartforsat(option) {
            // AJAX request to server-side script (PHP)
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetchDataForChartssat.php?option=' + option, true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var data = JSON.parse(xhr.responseText);
                    renderChartsat(data);
                    document.getElementById("sat_chartContainer").style.display = "block";
                } else {
                    console.error('Request failed');
                }
            };
            xhr.onerror = function () {
                console.error('Request failed');
            };
            xhr.send();
        }

        function renderChart(data) {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'filled Data Count (out of 91)',
                        data: data.values,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)', // Red color for unfilled data
                        borderColor: 'rgb(153, 102, 255)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 91 // Fix the maximum value on the y-axis
                            }
                        }]
                    },
                    // Set custom width and height
                    responsive: true, // Make the chart responsive
                    maintainAspectRatio: false, // Disable aspect ratio to adjust width and height
                    width: 300, // Adjust width as needed
                    height: 300, // Adjust height as needed
                },
                plugins: [ChartDataLabels]
            });
        }

        function renderChartsat(data) {
            var ctx = document.getElementById('myChartsat').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Satisfied Count (out of 91)',
                        data: data.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Blue color
                        borderColor: 'rgba(54, 162, 235, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 91 // Fix the maximum value on the y-axis
                            }
                        }]
                    },
                    // Set custom width and height
                    responsive: true, // Make the chart responsive
                    maintainAspectRatio: false, // Disable aspect ratio to adjust width and height
                    width: 300, // Adjust width as needed
                    height: 300, // Adjust height as needed,
                    plugins: {
                        datalabels: {
                            display: true // Display labels above each bar
                        }
                    }
                },
                plugins: [ChartDataLabels]

            });
        }

        function renderChartunsat(data) {
            var ctx = document.getElementById('myChartunsat').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Unsatisfied Count (out of 91)',
                        data: data.values,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Red color
                        borderColor: 'rgba(255, 99, 132, 1)', // Border color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                max: 91 // Fix the maximum value on the y-axis
                            }
                        }]
                    },
                    // Set custom width and height
                    responsive: true, // Make the chart responsive
                    maintainAspectRatio: false, // Disable aspect ratio to adjust width and height
                    width: 300, // Adjust width as needed
                    height: 300, // Adjust height as needed,
                    plugins: {
                        datalabels: {
                            display: true // Display labels above each bar
                        }
                    }
                },
                plugins: [ChartDataLabels]

            });
        }

        function logout() {
            window.location.href = "logout.php";
        }

        function home() {
            window.location.href = "dashboard_admin.php";
        }

        function report() {
            window.location.href = "viewreport.php";
        }

        function createpdf() {
            window.location.href = "createpdf.php";
        }

        function data() {
            window.location.href = "data.php";
        }
    </script>

    <?php include 'footer.php'; ?>

</body>

</html>
