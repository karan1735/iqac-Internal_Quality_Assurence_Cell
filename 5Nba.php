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
    <nav id=" navMenu">
        <div id="navbar"></div>
    </nav>
    <main>
        <h1>The National Board of Accreditation (NBA)</h1><br>
        <h2>ABOUT NBA:</h2>
        <p>The
            National Board of Accreditation (NBA), India was initially established by the All India
            Council of
            Technical Education (AICTE) under section 10(u) of AICTE Act, in the year 1994, in order to
            assess the
            qualitative competence of the programs offered by educational institution from diploma level
            to
            post-graduate level in engineering and technology, management, pharmacy, architecture and
            related
            disciplines, which are approved by AICTE.
            NBA came into existence as an independent autonomous body with effect from 7th January 2010
            with the
            objectives of assurance of quality and relevance to technical education, especially of the
            programs in
            professional and technical disciplines, i.e., Engineering and Technology, Management,
            Architecture,
            Pharmacy and Hotel Management and Catering Technology, through the mechanism of
            accreditation of
            programs offered by technical institutions.
        </p>

        <p>On June
            13, 2014, the National Board of Accreditation (NBA) of India became a permanent signatory
            member of the
            Washington Accord. The undergraduate and postgraduate programs accredited by the NBA under
            Tier-1 are
            eligible for recognition by other signatories of the Washington Accord.</p>

        <h2>NBA@KEC
        </h2>
        <p>KEC
            association with NBA dated back to 2001. Since 2001 all eligible programs have been
            accredited time to
            time.
            When NBA came under Washington accord, Kongu Engineering College was one among the few
            intuitions
            accredited by the new framework.
            Presently Kongu Engineering College offers 14 undergraduate (UG) programs and 8 postgraduate
            (PG)
            programs in Engineering and Technology. Out of these, 11 UG programs and MBA program were
            accredited
            under Tier I.

        </p>


        <?php
                        $folderPath = 'files/nba';
                        $files = scandir($folderPath);

                        echo "<div class='file-container'>";

                        foreach ($files as $file) {
                            // Ignore '.' and '..'
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                            <i class='fa-regular fa-file-lines'></i> $file
                                        </a>";
                            }
                        }

                        echo "</div>";
                        ?>

        <?php 
                    $folderPath = 'files/nba'; // Path to your folder
                    $files = scandir($folderPath);
                    
                    // Start the table
                    echo "<table class='file-table'>";
                    echo "<thead><tr><th>File Name</th></tr></thead>";
                    echo "<tbody>";
                    
                    // Loop through the files and display them
                    foreach ($files as $file) {
                        // Ignore '.' and '..'
                        if ($file !== '.' && $file !== '..') {
                            echo "<tr><td><a href='$folderPath/$file' class='file-link' target='_blank'>
                                    <i class='fa-regular fa-file-lines'></i> $file</a></td></tr>";
                        }
                    }
                    
                    echo "</tbody></table>";
                    
        ?>
        <iframe src="folder/nbatable.html" frameborder="0" width="100%" height="2000px"></iframe>





    </main>

</body>

</html>