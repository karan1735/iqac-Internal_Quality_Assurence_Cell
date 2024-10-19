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
            <button class="logoutbutton" onclick="back()" >Go Back</button>   
        </div>
    </div>
    <br>
    <main>
        <div class="undernav_update">
            <div class="left-top_update">
                <div class="topic">
                    <h3>Selection</h3>
                </div>
                <div class="content_update">
                    <div class="selectadmin">
                        <label for="criteria-admin-select">Select Criteria:</label>
                        <select id="criteria-admin-select" class="criteria-admin-select">
                            <option value="">-- Select Criteria --</option>
                            <?php foreach ($criteriaOptions as $criterion_id => $criterion_name) { ?>
                                <option value="<?php echo $criterion_id; ?>"><?php echo $criterion_name; ?></option>
                            <?php } ?>
                        </select>
                        <div class="search-box">
                            <label for="search-input">Search among Criteria:</label>
                            <input type="text" id="search-input" placeholder="Search...">
                        </div>
                        
                    </div>
                    <div class="left-right">
                            <button id="editcriteria" class="logoutbutton" onclick="editcriteria()">EDIT CRITERIAS</button>
                            <button id="cancel-criteria" class="logoutbutton" onclick="cancelcriteria()" style="display:none">CANCEL</button>
                     </div> 
                </div>
            </div>
            <div class="right-top">
                <table border="1" id="onlycriteria-table" style="display:none;">
                    <thead>
                        <tr>
                            <th>Criterion Name</th>
                            <th>Action</th> <!-- New column for edit button -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through all criteria options -->
                        <?php foreach ($criteriaOptions as $criterion_id => $criterion_name) { ?>
                            <tr>
                                <td><textarea rows="1" cols="80" data-criterion-id="<?php echo $criterion_id; ?>"><?php echo $criterion_name; ?></textarea></td>
                                <!-- Add an edit button for each criteria -->
                                <td>
                                    <button class="btn-save" onclick="editCriterion(<?php echo $criterion_id; ?>)">Edit</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
        <table border="1" id="criteria-table">
            <thead>
                <tr>
                    <th>Indicator</th>
                    <th>Measurement</th>
                    <th>Acceptance level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows will be dynamically populated based on the selected criteria -->
            </tbody>
        </table>
        <script>
            // Get references to elements
            const criteriaSelect = document.getElementById('criteria-admin-select');
            const tableBody = document.querySelector('#criteria-table tbody');

            // Event listener for criteria dropdown change
            criteriaSelect.addEventListener('change', function() {
                const selectedCriterionId = this.value;
                const selectedDepartmentId = '2';
                // Fetch data based on selected criterion and department, and update table
                fetchDataAndPopulateTable(selectedCriterionId, selectedDepartmentId);
            });
            
            function editcriteria(){
                document.getElementById('editcriteria').style.display="none";
                document.getElementById('onlycriteria-table').style.display="block";
                document.getElementById('cancel-criteria').style.display="block";
            }
            function cancelcriteria(){
                document.getElementById('editcriteria').style.display="block";
                document.getElementById('onlycriteria-table').style.display="none";
                document.getElementById('cancel-criteria').style.display="none";
            }
            
            // Function to edit the criterion
            function editCriterion(criterionId) {
                // Find the textarea containing the criterion name for the given criterionId
                const criterionTextarea = document.querySelector(`textarea[data-criterion-id="${criterionId}"]`);

                // Get the new criterion name from the textarea
                const newCriterionName = criterionTextarea.value;


                // If the user entered a new name and did not cancel
                if (newCriterionName !== null) {
                    // Send an AJAX request to update the criterion in the database
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'updateCriterion.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // If the update was successful, update the criterion name in the textarea
                                criterionTextarea.value = newCriterionName;
                                alert('Criterion updated successfully!');
                            } else {
                                console.error('Failed to update criterion');
                            }
                        }
                    };
                    // Send the criterion ID and the new criterion name as parameters
                    xhr.send(`criterion_id=${criterionId}&criterion_name=${newCriterionName}`);
                }
            }


            function fetchDataAndPopulateTable(criterionId, departmentId) {
                tableBody.innerHTML = '';

                // Fetch data for the selected criterion and department from the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `getDataforadmin.php?criterion_id=${criterionId}&department_id=${departmentId}`, true);
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const data = JSON.parse(xhr.responseText);
                            // Populate the table with the fetched data
                            data.forEach(row => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td><textarea rows="4" cols="50" data-row-id="${row.data_id}" data-input-type="indicator">${row.indicator_name}</textarea></td>
                                    <td><textarea rows="4" cols="50" data-row-id="${row.data_id}" data-input-type="measurement">${row.measurement_name}</textarea></td>
                                    <td><textarea rows="4" cols="50" data-row-id="${row.data_id}" data-input-type="acceptance" oninput="captureAcceptance(this, '${row.acceptance}')">${row.acceptance}</textarea></td>
                                    <td><button class="btn-save" onclick="saveData(${row.data_id}, ${row.indicator_id}, ${row.measurement_id})">Update</button></td>
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

            function captureAcceptance(textarea, prevAcceptance) {
                // Store the initial acceptance value as a data attribute on the textarea
                textarea.dataset.prevAcceptance = prevAcceptance;
            }

            function saveData(dataId, indicatorId, measurementId) {
                // Get the textarea elements corresponding to the edited content
                const indicatorTextarea = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="indicator"]`);
                const measurementTextarea = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="measurement"]`);
                const acceptanceTextarea = document.querySelector(`textarea[data-row-id="${dataId}"][data-input-type="acceptance"]`);

                // Get the new values from the textareas
                const newIndicatorValue = indicatorTextarea.value;
                const newMeasurementValue = measurementTextarea.value;
                const newAcceptanceValue = acceptanceTextarea.value;
                // Get the previous acceptance value from the dataset
                const prevAcceptanceValue = acceptanceTextarea.dataset.prevAcceptance || '';

                // Send an AJAX request to update the values in the database
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'updateData.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log('Data updated successfully');
                            // Display a success message
                            alert('Data updated successfully!');
                        } else {
                            console.error('Failed to update data');
                        }
                    }
                };
                // Send the data id, indicator id, and the new values as parameters
                xhr.send(`data_id=${dataId}&indicator_id=${indicatorId}&measurement_id=${measurementId}&indicator=${newIndicatorValue}&measurement=${newMeasurementValue}&prev_acceptance=${prevAcceptanceValue}&acceptance=${newAcceptanceValue}`);
            }

            // Event listener for search input
            const searchInput = document.getElementById('search-input');
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
            function back(){
                window.location.href = "data.php";
            }
        </script>
        <?php include 'footer.php'; ?>
    </main>
</body>
</html>
