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
        <h1>BEST PRACTICES</h1><br>
        <p>

            In pursuit of academic excellence and fostering a positive learning environment, it is
            essential for our educational community to adhere to establishing the best practices. These
            practices are designed to support effective teaching, enhance student learning, and promote
            a culture of continuous improvement and sustainable development.
        </p>

        <br>

        <?php
                        $folderPath = 'files/Best Practices'; 
                        $files = scandir($folderPath);

            
                        echo "<div class='file-container'>";

                        rsort($files);
                        foreach ($files as $file){
                            
                            if ($file !== '.' && $file !== '..') {
                                echo "<a href='$folderPath/$file' class='file-link' target='_blank'>
                                            <i class='fa-regular fa-file-lines'></i> $file
                                        </a>";
                            }
                        }

                     
                        echo "</div>";
                        ?>
    </main>

</body>

</html>