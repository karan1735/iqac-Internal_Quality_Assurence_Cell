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

    $query2 = "SELECT DISTINCT criterion_id, criterion_name FROM criteria";
    $resultCriteria = mysqli_query($con, $query2);

    $criteriaOptions = array();
    while ($row = mysqli_fetch_assoc($resultCriteria)) {
        $criteriaOptions[$row['criterion_id']] = $row['criterion_name'];
    }

    $query3 = "SELECT DISTINCT department_id, department_name FROM departments";
    $resultDepartments = mysqli_query($con, $query3);

    $departmentOptions = array();
    while ($row = mysqli_fetch_assoc($resultDepartments)) {
        $departmentOptions[$row['department_id']] = $row['department_name'];
    }
} else {
    echo "User ID not found in session.";
    exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
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
            <button class="logoutbutton" onclick="report()" >Report</button>
            <button class="logoutbutton" onclick="analysis()" >Analysis</button>
            <button class="logoutbutton" onclick="home()">Go Home</button>
            <button class="logoutbutton" onclick="logout()" >Logout</button>
        </div>
    </div>
    <br>
    <main>
        <div class="undernav_pdf">
            <div class="leftpdf">
                <div class="topic">
                    <h3>Selection</h3>
                </div>
                <div class="content_pdf">
                    <div class="selectadmin_pdf">
                        <label for="department-select">Select Department:</label>
                        <select id="department-select" class="department-select" onchange="toggleGenerateButton()">
                            <option value="">-- Select Department --</option>
                            <?php foreach ($departmentOptions as $department_id => $department_name) { ?>
                                <option value="<?php echo $department_id; ?>"><?php echo $department_name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="genbutton">
                    <button id="generateButton" class="generatebutton" onclick="generate()">Generate PDF</button>
                    </div>
                </div>
                <div id="loadingIcon" class="loading-icon" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> <!-- Font Awesome spinner icon -->
                    <span id="loadingProgress"></span>
                </div>
            </div>
            

       
            <div id="pdfDownloadBox" class="pdf-download-box" style="display:none">
                <button class="download-button" onclick="downloadPDF()">
                    <i class="fas fa-download download-icon"></i>Download PDF
                </button>     
            </div>
        </div>
        <script>
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

            function toggleGenerateButton() {
                var departmentSelect = document.getElementById('department-select');
                var generateButton = document.getElementById('generateButton');
                generateButton.disabled = departmentSelect.value === '';
            }

            function generate() {
                var departmentSelect = document.getElementById('department-select');
                if (departmentSelect.value === '') {
                    alert('Please select a department.');
                } else {
                    document.getElementById('pdfDownloadBox').style.display = "block";
                       
                }
            }


            function downloadPDF() {
                const selectedDepartmentId = document.getElementById('department-select').value;

                // AJAX request to fetch department name
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'getdepartmentname.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const departmentName = xhr.responseText;
                        // AJAX request to generate PDF
                        const generateXhr = new XMLHttpRequest();
                        generateXhr.open('GET', `generate_pdf.php?department_id=${selectedDepartmentId}`, true);
                        generateXhr.responseType = 'blob'; // Set response type to blob
                        generateXhr.onload = function () {
                            if (generateXhr.status === 200) {
                                const blob = new Blob([generateXhr.response], { type: 'application/pdf' });
                                const url = window.URL.createObjectURL(blob);

                                // Create a link element to trigger the download
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = `${departmentName}_iqac.pdf`; // Set PDF name using department name
                                document.body.appendChild(a);
                                a.click();

                                // Clean up
                                window.URL.revokeObjectURL(url);
                                document.body.removeChild(a);
                            } else {
                                console.error('Failed to generate PDF');
                            }
                        };
                        generateXhr.send();
                    } else {
                        console.error('Request failed. Status:', xhr.status);
                    }
                };
                xhr.send('department_id=' + selectedDepartmentId);

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
            function home(){
                window.location.href = "dashboard_admin.php";
            }

            function logout() {
                window.location.href = "logout.php";
            }
            function data(){
                window.location.href = "data.php";
            }
        </script>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>
