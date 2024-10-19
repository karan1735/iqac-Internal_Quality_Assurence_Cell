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
    $query = mysqli_query($con, "SELECT * FROM Users WHERE user_id = $id");

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
    <style>
       
    </style>
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
            <button class="logoutbutton" onclick="createpdf()" >PDF</button>
            <button class="logoutbutton" onclick="report()" >Report</button>
            <button class="logoutbutton" onclick="analysis()" >Analysis</button>
            <button class="logoutbutton" onclick="home()" >Go Home</button>
            <button class="logoutbutton" onclick="logout()" >Logout</button>
            
        </div>
    </div>
    <br>
    
    <main>
        <div class="selection_datum">
            <div class="button-container">
                <button class="logoutbutton" onclick="updateCriteria()">UPDATE</button>
            </div>
            <div class="search-btn_datum">
                <input type="text" id="searchBox" placeholder="Search..." oninput="search()">
            </div>
        </div>
    
    

    <tbody>
    <?php foreach ($criteria as $criterion) : ?>
    <h2><?php echo $criterion['criterion_name']; ?></h2>
    <table>
        <thead>
            <tr>
                <th>Indicator</th>
                <th>Measurements</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($indicators as $indicator) : ?>
                <?php if ($indicator['criterion_id'] == $criterion['criterion_id']) : ?>
                    <tr>
                        <td><?php echo $indicator['indicator_name']; ?></td>
                        <td>
                            <?php if (isset($measurements[$indicator['indicator_id']])) : ?>
                                <ul>
                                    <?php foreach ($measurements[$indicator['indicator_id']] as $measurement) : ?>
                                        <li><?php echo $measurement['measurement_name']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (isset($measurements[$indicator['indicator_id']])) : ?>
                                <ul>
                                    <?php foreach ($measurements[$indicator['indicator_id']] as $measurement) : ?>
                                        <?php if (isset($descriptions[$measurement['measurement_id']])) : ?>
                                            <li><?php echo $descriptions[$measurement['measurement_id']]['description']; ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>
    </tbody>
</table>

</main>

<script>
    function search() {
        var input, filter, tables, tr, td, i, j, txtValue;
        input = document.getElementById("searchBox");
        filter = input.value.toUpperCase();
        tables = document.querySelectorAll("table"); // Get all tables

        tables.forEach(function(table) {
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    var cell = td[j];
                    if (cell) {
                        txtValue = cell.textContent || cell.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        });
    }

    function updateCriteria() {
        window.location.href = "updateCriteria.php";
    }
    function logout(){
        window.location.href = "logout.php";
    }
    function home(){
        window.location.href = "dashboard_admin.php";
    }
    function report(){
        window.location.href = "viewreport.php";
    }
    function createpdf(){
        window.location.href = "createpdf.php";
    }
    function analysis(){
        window.location.href = "analysis.php";
    }

</script>


</body>

</html>
