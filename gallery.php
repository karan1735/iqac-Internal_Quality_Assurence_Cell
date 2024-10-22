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
    .layout-container {
        width: min(1000, 100%);
        margin: 0 auto;
        columns: 3 300px;
        column-gap: 1em;
    }

    img {
        display: block;
        margin-bottom: 1em;
        width: 100%;
        border-radius: 20px;
    }


    * {
        box-sizing: border-box;
    }

    .carousel {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: auto;
        overflow: hidden;
    }

    .carousel-inner {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-item {
        min-width: 100%;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-item img {
        width: 100%;
        height: auto;
        display: block;
    }

    button.prev,
    button.next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        border: none;
        padding: 10px;
        cursor: pointer;
        color: white;
        font-size: 18px;
        z-index: 1;
    }

    button.prev {
        left: 0;
    }

    button.next {
        right: 0;
    }

    @media (max-width: 768px) {

        button.prev,
        button.next {
            font-size: 14px;
            padding: 8px;
        }
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
        <div class="layout-container">
            <img src="gallery\1.jpeg" alt="" width="300px">
            <img src="gallery\2.jpeg" alt="" width="300px">
            <img src="gallery\3.jpeg" alt="" width="300px">
            <img src="gallery\4.jpeg" alt="" width="300px">
            <img src="gallery\5.jpeg" alt="" width="300px">
            <img src="gallery\6.jpeg" alt="" width="300px">
            <img src="gallery\7.jpeg" alt="" width="300px">
        </div>

        <div class="carousel">
            <div class="carousel-inner">
                <?php
        $imageFolder = 'gallery/';
        $images = glob($imageFolder . "*.{jpg,png,gif,jpeg}", GLOB_BRACE); // Fetch all images from folder

        foreach ($images as $index => $image) {
            $activeClass = ($index === 0) ? 'active' : ''; // Set the first image as active
            echo '<div class="carousel-item ' . $activeClass . '">';
            echo '<img src="' . $image . '" alt="Carousel Image">';
            echo '</div>';
        }
        ?>
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>

    </main>

</body>
<script>
let currentSlide = 0;

function showSlide(slideIndex) {
    const slides = document.querySelectorAll('.carousel-item');
    if (slideIndex >= slides.length) {
        currentSlide = 0;
    } else if (slideIndex < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = slideIndex;
    }
    const offset = -currentSlide * 100;
    document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
}

function moveSlide(step) {
    showSlide(currentSlide + step);
}

// Optional: Auto-play carousel
setInterval(() => moveSlide(1), 5000); // Change every 5 seconds
</script>

</html>