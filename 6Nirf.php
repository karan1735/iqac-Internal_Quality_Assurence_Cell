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
        <h1>National Institutional Ranking Framework (NIRF)</h1><br>
        <h2>NIRF &
            Other Rankings:</h2>
        <p>Rankings
            offer a critical overview of higher education institutions, providing a comparative analysis
            of their
            performance on various scales. Rankings are compiled by different organizations, each
            employing distinct
            methodologies to evaluate and rank institutions based on criteria such as academic
            reputation, research
            output, faculty quality, and student satisfaction.</p>

        <h2>RANKING @
            KEC:</h2>
        <p>National Institutional Ranking Framework (NIRF) is an initiative by the Government of India,
            introduced in 2015, to evaluate and rank higher education institutions across the country.
            Developed by the Ministry of Education, NIRF aims to provide a transparent and comprehensive
            assessment of institutions based on standardized criteria.</p>
        <p>
            Kongu Engineering College is continuously participating in NIRF ranking from its inception
            in the year 2015, for the year 2023 Kongu Engineering is ranked in the Band of 101 to 150 in
            the Engineering Category and in the Band of 51 to 100 in the Innovation Category.
            Additionally, KEC has secured top positions in several rankings conducted by various
            organizations and magazines like, Careers 360, Business world etc.
        </p><br>
        <?php
                        $folderPath = 'files/NIRF & Other Ranking'; // Path to your folder
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