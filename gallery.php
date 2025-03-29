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
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        /* Responsive columns */
        gap: 10px;
        /* Space between grid items */
        margin: 20px 0;
        /* Margin above and below the grid */
    }

    .image-item {
        border: 1px solid #ccc;
        /* Optional border for image items */
        border-radius: 4px;
        /* Optional rounded corners */
        overflow: hidden;
        /* Ensure images don't overflow */
        text-align: center;
        /* Center align text */
    }

    .image-item img {
        width: 100%;
        /* Make images responsive */
        height: auto;
        /* Maintain aspect ratio */
    }

    .image-item p {
        margin: 5px 0;
        /* Margin for the filename */
        font-size: 14px;
        /* Font size for filename */
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
        <h1>GALLERY</h1><br>
        <?php
$baseFolder = 'gallery';
$archivePath = "$baseFolder/archive";

// Ensure the archive directory exists
if (!is_dir($archivePath)) {
    mkdir($archivePath, 0777, true);
}

// Get all folders inside `gallery/` excluding `archive`
$folders = array_filter(glob("$baseFolder/*"), 'is_dir');
$folders = array_diff($folders, [$archivePath]); // Remove archive folder from the list

$oneWeekAgo = time() - (7 * 24 * 60 * 60); // 7 days ago

// Function to extract date from filename (Assuming format: image_YYYY-MM-DD.jpg)
function extractDateFromFilename($filename) {
    if (preg_match('/(\d{4}-\d{2}-\d{2})/', $filename, $matches)) {
        return strtotime($matches[1]); // Convert extracted date to timestamp
    }
    return false;
}

// Process each folder
foreach ($folders as $folderPath) {
    $images = array_diff(scandir($folderPath), ['.', '..']);

    foreach ($images as $image) {
        $imagePath = "$folderPath/$image";
        $destinationPath = "$archivePath/$image";

        // Only process image files
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $image)) {
            $imageDate = extractDateFromFilename($image);

            if ($imageDate !== false && $imageDate < $oneWeekAgo) {
                // Move the image to archive
                rename($imagePath, $destinationPath);
            }
        }
    }
}

// Refresh the folders list after moving images
$folders = array_filter(glob("$baseFolder/*"), 'is_dir');
$folders = array_diff($folders, [$archivePath]); // Remove archive folder from the list

echo '<div class="gallery-container">';

// Store all images for JavaScript navigation
$allImages = [];

// Display Images from each folder
foreach ($folders as $folderPath) {
    $folderName = basename($folderPath);
    $images = array_diff(scandir($folderPath), ['.', '..']);

    if (!empty($images)) {
        echo "<h2 class='folder-name'>$folderName</h2>";
        echo '<div class="gallery-grid">';

        foreach ($images as $image) {
            $imageSrc = "$folderPath/$image";
            $allImages[] = $imageSrc;
            echo "<div class='gallery-item' onclick='openModal(\"$imageSrc\")'>
                    <img src='$imageSrc' alt='Image'>
                  </div>";
        }
        echo "</div>";
    }
}

// Display Archived Images
$archiveImages = array_diff(scandir($archivePath), ['.', '..']);

if (!empty($archiveImages)) {
    echo "<h2 class='folder-name'>Archived Images</h2>";
    echo '<div class="gallery-grid">';

    foreach ($archiveImages as $image) {
        $imageSrc = "$archivePath/$image";
        $allImages[] = $imageSrc;
        echo "<div class='gallery-item' onclick='openModal(\"$imageSrc\")'>
                <img src='$imageSrc' alt='Image'>
              </div>";
    }
    echo "</div>";
}

echo '</div>';
?>
    </main>
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <a class="prev" onclick="prevImage()">&#10094;</a>
        <a class="next" onclick="nextImage()">&#10095;</a>
    </div>

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

    /* Modal styles */
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
    </style>

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
        if (currentImageIndex > 0) {
            currentImageIndex--;
        } else {
            currentImageIndex = imageList.length - 1; // Loop back to the last image
        }
        document.getElementById("modalImage").src = imageList[currentImageIndex];
    }

    function nextImage() {
        if (currentImageIndex < imageList.length - 1) {
            currentImageIndex++;
        } else {
            currentImageIndex = 0; // Loop back to the first image
        }
        document.getElementById("modalImage").src = imageList[currentImageIndex];
    }

    // Close modal on click outside of image
    document.getElementById("imageModal").addEventListener("click", function(event) {
        if (event.target === this) {
            closeModal();
        }
    });
    </script>

</html>