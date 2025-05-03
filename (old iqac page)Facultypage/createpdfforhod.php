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
    $query2 = mysqli_query($con,"SELECT department_id FROM users WHERE user_id=$id");
    if($query2){
        if(mysqli_num_rows($query2)>0){
            $depid = mysqli_fetch_assoc($query2);
            $dep = $depid['department_id'];
        }
        else{
            echo "Not found";
        }
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
            <button class="logoutbutton" onclick="home()">Go Home</button>
            <button class="logoutbutton" onclick="logout()" >Logout</button>
        </div>
    </div>
    <br>
    <main>
        <div class="undernav_pdf">
            <div class="leftpdf">
                <div class="topic">
                <input type="hidden" id="departmentId" value="<?php echo $dep; ?>">

                    <h3>Generate PDF</h3>
                </div>
                <div class="content_pdf">
                    Click here to generate pdf of your department's data:
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
                    function generate() {
            var departmentId = document.getElementById('departmentId').value;
            if (departmentId === '') {
                alert('Department ID not found.');
            } else {
                document.getElementById('pdfDownloadBox').style.display = "block";
            }
        }

        function downloadPDF() {
            var departmentId = document.getElementById('departmentId').value;
            if (departmentId === '') {
                alert('Department ID not found.');
            } else {
                // AJAX request to generate PDF
                var generateXhr = new XMLHttpRequest();
                generateXhr.open('GET', `generate_pdf.php?department_id=${departmentId}`, true);
                generateXhr.responseType = 'blob'; // Set response type to blob
                generateXhr.onload = function () {
                    if (generateXhr.status === 200) {
                        var blob = new Blob([generateXhr.response], { type: 'application/pdf' });
                        var url = window.URL.createObjectURL(blob);

                        // Create a link element to trigger the download
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = `IQAC_Report_${departmentId}.pdf`; // Set PDF name using department ID
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
            }
        }

            function logout(){
                window.location.href = "logout.php";
            }
            function report(){
                window.location.href = "report.php";
            }
            function home(){
                window.location.href = "dashboard_hod.php";
            }

        </script>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>
