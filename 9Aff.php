<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQAC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!------------------------------------------------------------------------------------------------------------->
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <style>
    .accordion {
        width: 100%;
        margin: 0 auto;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
    }

    .accordion-item {
        border-bottom: 1px solid #e0e0e0;
    }

    .accordion-item:last-child {
        border-bottom: none;
    }

    .accordion-title {
        padding: 15px;
        background-color: #f8f9fa;
        color: #000000;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .accordion-title .arrow {
        transition: transform 0.3s ease;
        font-size: 18px;
    }

    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        padding: 0 15px;
        background-color: #ffffff;
        color: #333;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .accordion-content p {
        padding: 15px 0;
        line-height: 1.6;
    }

    .accordion-item.active .accordion-content {
        max-height: fit-content;
        /* You can adjust this */
        padding: 15px;
    }

    .accordion-item.active .accordion-title {
        background-color: #ff4c4c;
        color: white;
        box-shadow: 0 4px 10px rgba(255, 76, 76, 0.2);
    }

    .accordion-item.active .arrow {
        transform: rotate(180deg);
    }

    .accordion-item:hover .accordion-title {
        background-color: #ffecec;
        color: #ff4c4c;
    }
    </style>

    <style>
    /* Basic styling for the container */
    .tab-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background: linear-gradient(135deg, #f0f4ff, #e3e9ff);
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Styling the outer tabs */
    .outer-tab-nav {
        display: flex;
        justify-content: space-around;
        background: linear-gradient(135deg, #c3d1ff, #d1d9ff);
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .outer-tab-nav button {
        background: linear-gradient(145deg, #d1e3ff, #b0c1ff);
        border: none;
        padding: 14px 28px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        border-radius: 10px;
        color: #333;
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease, transform 0.2s ease;
    }

    .outer-tab-nav button:hover {
        background: linear-gradient(145deg, #b9d0ff, #9fb9ff);
        transform: translateY(-4px);
        box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.2);
    }

    .outer-tab-nav button.active {
        background: linear-gradient(145deg, #5f8fff, #4b7dff);
        color: #fff;
        transform: translateY(0);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.25);
    }

    /* Styling the inner tabs */
    .inner-tab-nav {
        display: flex;
        justify-content: space-around;
        background: linear-gradient(135deg, #e6edff, #dfe5ff);
        padding: 10px;
        margin-top: 15px;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .inner-tab-nav button {
        background: linear-gradient(145deg, #dfe5ff, #c7d4ff);
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        border-radius: 8px;
        color: #444;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .inner-tab-nav button:hover {
        background: linear-gradient(145deg, #c7d4ff, #aebfff);
        transform: translateY(-3px);
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
    }

    .inner-tab-nav button.active {
        background: linear-gradient(145deg, #4b7dff, #365bff);
        color: #fff;
        transform: translateY(0);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Styling for the tab content */
    .tab-content {
        display: none;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #ddd;
        margin-top: 15px;
        background: linear-gradient(145deg, #ffffff, #f9faff);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: opacity 0.4s ease, transform 0.4s ease;
        opacity: 0;
        transform: scale(0.95);
    }

    /* Show the active content with enhanced animation */
    .tab-content.active {
        display: block;
        opacity: 1;
        transform: scale(1);
    }

    /* Adding some color to text within content */
    .tab-content h2 {
        font-size: 24px;
        color: #365bff;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .tab-content p {
        font-size: 16px;
        color: #333;
        line-height: 1.6;
    }

    /* Media query for mobile responsiveness */
    @media (max-width: 768px) {

        .outer-tab-nav button,
        .inner-tab-nav button {
            padding: 10px 16px;
            font-size: 14px;
        }

        .tab-content {
            padding: 20px;
        }
    }
    </style>


</head>

<body>
    <header id="header">
    </header>
    <nav id="navMenu">
        <div id="navbar"></div>
    </nav>
    <main>

        <!--content start-->
        <h1>Affiliation and Approvals:</h1><br>
        <p>
            Affiliation under a university is a formal process through which academic institutions gain
            recognition
            and accreditation to offer programs and confer degrees that are officially validated by the
            university.
            This affiliation ensures that the educational standards and quality of the affiliated
            institutions align
            with the university's guidelines and regulations. Kongu Engineering College was established
            in the year
            1984, approved by AICTE, New Delhi, and affiliated with Anna University, Chennai. Initially,
            it was
            affiliated with Bharathiar University up to 2000. Subsequently, it was affiliated with Anna
            University
            from 2001. The college was granted autonomous status from 2007 by UGC.</p><br>
        <div class="tab-container">
            <!-- Outer Tab Navigation -->
            <div class="tab-nav outer-tab-nav">
                <button class="tab-link active" onclick="openTab(event, 'tab1', 'outer')">Autonomous</button>
                <button class="tab-link" onclick="openTab(event, 'tab2', 'outer')">AU Affiliation</button>
                <button class="tab-link" onclick="openTab(event, 'tab3', 'outer')">AICTE</button>
            </div>

            <!-- Outer Tab Contents -->
            <div id="tab1" class="tab-content outer-tab-content active">
                <?php
        $folderPath = 'files/Affliation & Approval/AUTONOMOUS';
        $files = scandir($folderPath);
        $files = array_diff($files, array('.', '..'));
        rsort($files);
        echo "<div class='file-container'>";
        foreach ($files as $file) {
            echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
            <i class='fa-regular fa-file-lines'></i> $file
        </a>";
        }
        echo "</div>";
        ?>
            </div>

            <div id="tab2" class="tab-content outer-tab-content">
                <?php
        $folderPath = 'files/Affliation & Approval/AU Affiliation';
        $files = scandir($folderPath);
        $files = array_diff($files, array('.', '..'));
        rsort($files);
        echo "<div class='file-container'>";
        foreach ($files as $file) {
            echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
            <i class='fa-regular fa-file-lines'></i> $file
        </a>";
        }
        echo "</div>";
        ?>
            </div>

            <div id="tab3" class="tab-content outer-tab-content">
                <!-- Inner Tab Navigation -->
                <div class="tab-nav inner-tab-nav">
                    <button class="tab-link active" onclick="openTab(event, 'ENG', 'inner')">ENG</button>
                    <button class="tab-link" onclick="openTab(event, 'MBA', 'inner')">MBA</button>
                    <button class="tab-link" onclick="openTab(event, 'MCA', 'inner')">MCA</button>
                </div>

                <!-- Inner Tab Contents -->
                <div id="ENG" class="tab-content inner-tab-content active">
                    <?php
            $folderPath = 'files/Affliation & Approval/AICTE/ENG';
            $files = scandir($folderPath);
            $files = array_diff($files, array('.', '..'));
            rsort($files);
            echo "<div class='file-container'>";
            foreach ($files as $file) {
                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
            <i class='fa-regular fa-file-lines'></i> $file
            </a>";
            }
            echo "</div>";
            ?>
                </div>

                <div id="MBA" class="tab-content inner-tab-content">
                    <?php
            $folderPath = 'files/Affliation & Approval/AICTE/MBA';
            $files = scandir($folderPath);
            $files = array_diff($files, array('.', '..'));
            rsort($files);
            echo "<div class='file-container'>";
            foreach ($files as $file) {
                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
            <i class='fa-regular fa-file-lines'></i> $file
            </a>";
            }
            echo "</div>";
            ?>
                </div>

                <div id="MCA" class="tab-content inner-tab-content">
                    <?php
            $folderPath = 'files/Affliation & Approval/AICTE/MCA';
            $files = scandir($folderPath);
            $files = array_diff($files, array('.', '..'));
            rsort($files);
            echo "<div class='file-container'>";
            foreach ($files as $file) {
                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
            <i class='fa-regular fa-file-lines'></i> $file
            </a>";
            }
            echo "</div>";
            ?>
                </div>
            </div>
        </div>

        <footer id="footer"></footer>
    </main>

</body>
<script>
const accordionItems = document.querySelectorAll('.accordion-item');

accordionItems.forEach(item => {
    const title = item.querySelector('.accordion-title');
    title.addEventListener('click', () => {
        const isActive = item.classList.contains('active');

        accordionItems.forEach(i => i.classList.remove('active'));

        if (!isActive) {
            item.classList.add('active');
        }
    });
});
</script>
<script>
function openTab(evt, tabName, tabType) {
    var i, tabcontent, tablinks;

    // Determine which tab set to operate on
    if (tabType === 'outer') {
        tabcontent = document.querySelectorAll('.outer-tab-content');
        tablinks = document.querySelectorAll('.outer-tab-nav .tab-link');
    } else if (tabType === 'inner') {
        tabcontent = document.querySelectorAll('.inner-tab-content');
        tablinks = document.querySelectorAll('.inner-tab-nav .tab-link');
    } else {
        return;
    }

    // Hide all tab contents of the specified type
    tabcontent.forEach(function(content) {
        content.classList.remove('active');
    });

    // Remove 'active' class from all tab links of the specified type
    tablinks.forEach(function(link) {
        link.classList.remove('active');
    });

    // Show the current tab and add 'active' class to the clicked button
    document.getElementById(tabName).classList.add('active');
    evt.currentTarget.classList.add('active');
}

// Optional: Initialize the first tab as active (if not already set in HTML)
document.addEventListener('DOMContentLoaded', function() {
    // Outer Tabs
    var outerActive = document.querySelector('.outer-tab-nav .tab-link.active');
    if (outerActive) {
        var outerTabName = outerActive.getAttribute('onclick').split("'")[3];
        document.getElementById(outerTabName).classList.add('active');
    }

    // Inner Tabs
    var innerActive = document.querySelector('.inner-tab-nav .tab-link.active');
    if (innerActive) {
        var innerTabName = innerActive.getAttribute('onclick').split("'")[3];
        document.getElementById(innerTabName).classList.add('active');
    }
});
</script>

<script>
fetch("folder/accordian.html")
    .then((response) => response.text())
    .then((data) => {
        document.getElementById("accordian").innerHTML = data;
    });
</script>

</html>