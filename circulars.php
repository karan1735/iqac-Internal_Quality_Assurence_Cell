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
    .pdf-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .pdf-wrapper:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .pdf-wrapper iframe {
        width: 100%;
        height: 300px;
        border: none;
        border-radius: 10px;
    }

    .overlay-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        opacity: 0;
        cursor: pointer;
    }

    .file-name {
        margin-top: 10px;
        font-weight: 600;
        color: #333;
        font-family: Arial, sans-serif;
        text-align: center;
        font-size: 1rem;
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
        <h1>CIRCULARS</h1><br>
        <?php
        $baseFolder = 'files/circular';
        $archivePath = "$baseFolder/archive";

        // Ensure the archive directory exists
        if (!is_dir($archivePath)) {
            mkdir($archivePath, 0777, true);
        }

        // Get all folders inside `files/circular/` excluding `archive`
        $folders = array_filter(glob("$baseFolder/*"), 'is_dir');
        $folders = array_diff($folders, [$archivePath]); // Remove archive folder from the list
        
        $oneWeekAgo = time() - (7 * 24 * 60 * 60); // 7 days ago
        
        // Function to extract date from filename (Assuming format: circular_YYYY-MM-DD.pdf)
        function extractDateFromFilename($filename)
        {
            if (preg_match('/(\d{4}-\d{2}-\d{2})/', $filename, $matches)) {
                return strtotime($matches[1]); // Convert extracted date to timestamp
            }
            return false;
        }

        // Process each folder
        foreach ($folders as $folderPath) {
            $files = array_diff(scandir($folderPath), ['.', '..']);

            foreach ($files as $file) {
                $filePath = "$folderPath/$file";
                $destinationPath = "$archivePath/$file";

                // Only process PDF files
                if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $fileDate = extractDateFromFilename($file);

                    if ($fileDate !== false && $fileDate < $oneWeekAgo) {
                        // Move the file to archive
                        rename($filePath, $destinationPath);
                    }
                }
            }
        }

        // Refresh the folders list after moving files
        $folders = array_filter(glob("$baseFolder/*"), 'is_dir');
        $folders = array_diff($folders, [$archivePath]); // Remove archive folder from the list
        
        // Display Circulars from each folder
        foreach ($folders as $folderPath) {
            $folderName = basename($folderPath);
            $files = array_diff(scandir($folderPath), ['.', '..']);

            if (!empty($files)) {
                echo "<h2 class='folder-name'>$folderName</h2>";
                echo "<div class='file-container'>";
                rsort($files);

                foreach ($files as $file) {
                    echo "<div class='pdf-wrapper'>
                    <iframe src='$folderPath/$file#toolbar=0&navpanes=0&scrollbar=0' width='100%' height='100%' scrolling='no'></iframe>
                    <a href='$folderPath/$file' target='_blank' class='overlay-link'></a>
                    <p class='file-name'>$file</p>
                  </div>";
                }
                echo "</div>";
            }
        }

        // Display Archived Circulars
        $archiveFiles = array_diff(scandir($archivePath), ['.', '..']);

        if (!empty($archiveFiles)) {
            echo "<h2 class='folder-name'>Archived Circulars</h2>";
            echo "<div class='file-container'>";
            rsort($archiveFiles);

            foreach ($archiveFiles as $file) {
                echo "<div class='pdf-wrapper'>
                <iframe src='$archivePath/$file#toolbar=0&navpanes=0&scrollbar=0' width='100%' height='100%' scrolling='no'></iframe>
                <a href='$archivePath/$file' target='_blank' class='overlay-link'></a>
                <p class='file-name'>$file</p>
              </div>";
            }
            echo "</div>";
        }
        ?>


    </main>

</body>

</html>