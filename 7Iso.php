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
</head>

<body>
    <header id="header">
    </header>
    <nav id="navMenu">
        <div id="navbar"></div>
    </nav>
    <main>
        <h1>International Organization for Standardization(ISO)</h1><br>
        <!--content start-->
        <h2>ABOUT ISO:
        </h2>
        <p>ISO
            certifications represent a commitment to excellence and adherence to internationally
            recognized
            standards of quality, safety, and efficiency. The International Organization for
            Standardization (ISO)
            is an independent, non-governmental international organization that develops and publishes a
            wide range
            of standards across various industries and sectors. ISO certifications are valuable for
            organizations
            seeking to enhance their operational efficiency, improve customer satisfaction, and gain a
            competitive
            edge in the Nation and beyond.</p>

        <h2>ISO@KEC:</h2>
        <p>Kongu
            Engineering College is certified by ISO in accordance with ISO 9001:1993 from 1999,
            subsequently by ISO
            9001:2000, and currently by ISO 9001:2015 in 2020, and recertified in 2023 for the scope of
            Curriculum
            Development and Conducting Undergraduate, Post Graduate, and Research Programmes in
            Engineering, Applied
            Sciences, and Management.</p><br>

        <?php
                        $folderPath = 'files/ISO'; // Path to your folder
                        $files = scandir($folderPath);

                        // Start the file container
                        echo "<div class='file-container'>";
                        rsort($files);
                        // Loop through the files and display them
                        foreach ($files as $file) {
                            // Ignore '.' and '..'
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                            <i class='fa-regular fa-file-lines'></i> $file
                                        </a>";
                            }
                        }

                        // End the file container
                        echo "</div>";
                        ?>

    </main>

</body>

</html>