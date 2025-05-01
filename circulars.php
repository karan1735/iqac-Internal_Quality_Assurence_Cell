<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IQAC - Circulars</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <style>
        .pdf-wrapper {
            border-radius: 6px;
            transition: transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            position: relative;
            background-color: #fcfcfc;
            padding: 1rem;
        }

        .pdf-wrapper:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }

        .pdf-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            padding: 0.5rem;
        }

        .pdf-icon {
            margin-right: 1rem;
            color: #e74c3c;
            font-size: 1.5rem;
        }

        .file-name {
            margin: 0;
            font-size: 1rem;
            color: #444;
            font-weight: 500;
            flex-grow: 1;
        }

        .file-date {
            color: #666;
            font-size: 0.85rem;
            margin-left: 1rem;
        }

        .folder-name {
            color: #333;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eee;
            font-size: 1.5rem;
        }

        .file-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .no-files {
            text-align: center;
            color: #777;
            padding: 1.25rem;
            font-style: italic;
        }

        @media (max-width: 576px) {
            .file-container {
                grid-template-columns: 1fr;
            }
        }

        .archive-toggle {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
        }

        .archive-toggle:hover {
            background-color: #e9ecef;
        }

        .archive-toggle i {
            transition: transform 0.3s ease;
        }

        .collapse:not(.show) + .archive-toggle i {
            transform: rotate(-90deg);
        }
    </style>
</head>

<body>
    <header id="header"></header>
    <nav id="navMenu">
        <div id="navbar"></div>
    </nav>
    <main class="container py-4">
        <h1 class="text-center mb-4">CIRCULARS</h1>

        <?php
        function moveOldFilesToArchive($directory, $archivePath) {
            $twoDaysAgo = time() - (2 * 24 * 60 * 60);
            $files = array_diff(scandir($directory), ['.', '..']);

            foreach ($files as $file) {
                $filePath = "$directory/$file";
                if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $fileTime = filectime($filePath);
                    if ($fileTime < $twoDaysAgo) {
                        if (!file_exists($archivePath)) {
                            mkdir($archivePath, 0777, true);
                        }
                        rename($filePath, "$archivePath/$file");
                    }
                }
            }
        }

        function displayPDFs($directory, $showFolderName = true) {
            $files = array_diff(scandir($directory), ['.', '..']);
            $pdfFiles = array_filter($files, function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
            });

            if (!empty($pdfFiles)) {
                echo "<div class='folder-section'>";
                if ($showFolderName) {
                    echo "<h2 class='folder-name'>" . basename($directory) . "</h2>";
                }
                echo "<div class='file-container'>";
                rsort($pdfFiles);
                foreach ($pdfFiles as $file) {
                    $filePath = "$directory/$file";
                    $fileDate = date("Y-m-d", filectime($filePath));
                    echo "<div class='pdf-wrapper'>
                            <a href='$filePath' target='_blank' class='pdf-link'>
                                <i class='fas fa-file-pdf pdf-icon'></i>
                                <span class='file-name'>$file</span>
                                <span class='file-date'>$fileDate</span>
                            </a>
                        </div>";
                }
                echo "</div></div>";
            }
        }

        $mainDirectory = 'files/circular';
        $archiveDirectory = "$mainDirectory/archive";

        if (is_dir($mainDirectory)) {
            moveOldFilesToArchive($mainDirectory, $archiveDirectory);
            $subDirectories = array_filter(glob($mainDirectory . '/*'), 'is_dir');
            foreach ($subDirectories as $dir) {
                if (basename($dir) !== 'archive') {
                    moveOldFilesToArchive($dir, $archiveDirectory);
                }
            }

            displayPDFs($mainDirectory, false);
            foreach ($subDirectories as $dir) {
                if (basename($dir) !== 'archive') {
                    displayPDFs($dir, true);
                }
            }

            if (is_dir($archiveDirectory)) {
                echo "<div class='archive-section'>";
                echo "<button class='archive-toggle' data-toggle='collapse' data-target='#archiveContent' aria-expanded='false' aria-controls='archiveContent'>
                        <span>Archived Circulars</span>
                        <i class='fas fa-chevron-down ml-2'></i>
                    </button>";
                echo "<div class='collapse' id='archiveContent'>";
                displayPDFs($archiveDirectory, false);
                echo "</div></div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Directory '$mainDirectory' not found!</div>";
        }
        ?>
    </main>
</body>

</html>
