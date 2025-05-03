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
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <style>
        .gallery-container {
            text-align: center;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 4px;
            padding: 10px;
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .gallery-item img:hover {
            transform: scale(1.1);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            text-align: center;
        }

        .modal-content {
            margin: auto;
            display: block;
            top: 100px;
            max-width: 50%;
            max-height: 100%;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            font-size: 40px;
            color: white;
            cursor: pointer;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            color: white;
            cursor: pointer;
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
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

    <main>
        <h1>GALLERY</h1><br>
        <?php
        $imageFolder = '../gallery';
        $archiveFolder = "$imageFolder/archive";
        $sevenDaysAgo = time() - (7 * 24 * 60 * 60);

        // Function to recursively get all images from a directory
        function getImagesFromDirectory($dir, $excludeDir = null) {
            $images = [];
            $files = array_diff(scandir($dir), ['.', '..']);
            
            foreach ($files as $file) {
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    // Skip the excluded directory
                    if ($excludeDir && $path === $excludeDir) {
                        continue;
                    }
                    $images = array_merge($images, getImagesFromDirectory($path, $excludeDir));
                } else if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    $images[] = $path;
                }
            }
            return $images;
        }

        // Ensure archive folder exists
        if (!is_dir($archiveFolder)) {
            mkdir($archiveFolder, 0777, true);
        }

        // Get all current images (excluding archive folder)
        $currentImages = getImagesFromDirectory($imageFolder, $archiveFolder);
        
        // Get archived images
        $archivedImages = getImagesFromDirectory($archiveFolder);
        $allImages = array_merge($currentImages, $archivedImages);

        // Start Gallery Display
        echo '<div class="gallery-container">';
        
        // Current Images
        if (!empty($currentImages)) {
            echo "<h2 class='folder-name'>Current Images</h2>";
            echo '<div class="gallery-grid">';
            foreach ($currentImages as $imagePath) {
                echo "<div class='gallery-item' onclick='openModal(\"$imagePath\")'>
                        <img src='$imagePath' alt='Image'>
                    </div>";
            }
            echo '</div>';
        } else {
            echo "";
        }

        // Archived Images
        if (!empty($archivedImages)) {
            echo "<div class='archived-section'>";
            echo "<button class='archive-toggle' data-toggle='collapse' data-target='#archivedImages' aria-expanded='false' aria-controls='archivedImages'>
                    <span>Archived Images</span>
                    <i class='fas fa-chevron-down ml-2'></i>
                  </button>";
            echo "<div class='collapse' id='archivedImages'>";
            echo '<div class="gallery-grid">';
            foreach ($archivedImages as $imagePath) {
                echo "<div class='gallery-item' onclick='openModal(\"$imagePath\")'>
                        <img src='$imagePath' alt='Image'>
                    </div>";
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        ?>
    </main>

    <!-- Modal -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <a class="prev" onclick="prevImage()">&#10094;</a>
        <a class="next" onclick="nextImage()">&#10095;</a>
    </div>

    <script>
        let currentImageIndex = 0;
        let imageList = <?php echo json_encode($allImages); ?>;

        function openModal(src) {
            document.getElementById("imageModal").style.display = "block";
            document.getElementById("modalImage").src = src;
            currentImageIndex = imageList.indexOf(src);
        }

        function closeModal() {
            document.getElementById("imageModal").style.display = "none";
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : imageList.length - 1;
            document.getElementById("modalImage").src = imageList[currentImageIndex];
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex < imageList.length - 1) ? currentImageIndex + 1 : 0;
            document.getElementById("modalImage").src = imageList[currentImageIndex];
        }

        document.getElementById("imageModal").addEventListener("click", function (event) {
            if (event.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>
