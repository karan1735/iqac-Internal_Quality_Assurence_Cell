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
        <h1>The National Assessment and Accreditation Council(NAAC)</h1><br>
        <h2>ABOUT NAAC:</h2>
        <p>
            The National Assessment and Accreditation Council (NAAC) is an autonomous body established
            by
            the University
            Grants Commission (UGC) of India to assess and accredit higher education institutions in the
            country.
            Since 1994, NAAC plays a pivotal role in ensuring the quality and standards of education
            provided by
            universities and colleges. Its primary objective is to promote a culture of continuous
            improvement and
            excellence in higher education institutions by evaluating their performance across various
            parameters.
            Accreditation by NAAC is not just a certification but a commitment to maintain and improve
            quality
            standards in education, fostering an environment of academic excellence and accountability.
            It
            also aids
            stakeholders, including students, faculty, and employers, in making informed decisions about
            the
            institution's credibility and educational offerings.
        </p>
        <h2>NAAC@KEC:</h2>
        <p>
            Kongu Engineering College first underwent NAAC assessment in 2016, where it received an ‘A’
            grade. With a
            commitment to continuous improvement, the college achieved the highest grade of ‘A++’ in its
            second
            cycle, valid for the years 2023-2028.
        </p><br>

        <?php
                        $folderPath = 'files/naac/NAAC Certificate';
                        $files = scandir($folderPath);

                        echo "<div class='file-container'>";
                        rsort($files);

                        foreach ($files as $file) {
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                            <i class='fa-regular fa-file-lines'></i> $file
                                        </a>";
                            }
                        }

                        echo "</div>";
                        ?>



        <br>
        <h2>SSR</h2><br>
        <?php
                        $folderPath = 'files/naac/NAAC SSR'; // Path to your folder
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

        <br>
        <h2>AQAR</h2><br>

        <?php
                        $folderPath = 'files/naac/AQAR'; // Path to your folder
                        $files = scandir($folderPath);

                        // Start the file container
                        echo "<div class='file-container'>";
                        rsort($files);

                        // Loop through the files and display them
                        foreach ($files as $file) {
                            // Ignore '.' and '..'
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                    <i class='fa-regular fa-file-lines'></i> $file</a>";
                            }
                        }

                        // End the file container
                        echo "</div>";
                        ?>

    </main>

</body>

</html>