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
        <h1>MEETING & MINUTES:</h1><br>
        <p>Meetings
            are essential components of effective administration, serving as platforms for
            decision-making,
            coordination, and strategic planning within organizations. Conducting these meetings with
            clarity and
            structure is crucial for achieving productive outcomes and ensuring that all participants
            are aligned
            with organizational goals. Properly conducted administrative meetings facilitate the
            efficient exchange
            of information, foster collaborative problem-solving, and support the implementation of
            policies and
            initiatives.</p>


        <p>In Kongu
            Engineering College, the IQAC Core Committee meets at least twice a year. Internal IQAC
            meetings with
            the department coordinators occur every six months, and IQAC members meet as and when
            required.</p>

        <br>

        <?php
        $folderPath = 'files/Meeting & Minutes'; // Path to your folder
        $files = scandir($folderPath);

        // Remove '.' and '..' from the files array
        $files = array_diff($files, array('.', '..'));

        // Sort the files in descending order
        rsort($files);

        // Start the file container
        echo "<div class='file-container'>";

        // Loop through the sorted files and display them
        foreach ($files as $file) {
            echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                    <i class='fa-regular fa-file-lines'></i> $file
                </a>";
        }

        // End the file container
        echo "</div>";
        ?>


    </main>

</body>

</html>