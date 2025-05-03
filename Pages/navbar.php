<ul>
    <li><a href="1Home.php" class="nav-link"><i class="bi bi-house"></i>HOME</a></li>
    <li><a href="2Composition.php" class="nav-link"><i class="bi bi-people"></i>COMPOSITION</a></li>
    <li><a href="3Org.php" class="nav-link"><i class="bi bi-diagram-3"></i>ORGANIZATION HIERARCHY</a></li>
    <li><a href="4Naac.php" class="nav-link"><i class="bi bi-bar-chart-line"></i>NAAC</a></li>
    <li><a href="5Nba.php" class="nav-link"><i class="bi bi-mortarboard"></i>NBA</a></li>
    <li><a href="6Nirf.php" class="nav-link"><i class="bi bi-check2-all"></i>NIRF & OTHER RANKINGS</a></li>
    <li><a href="7Iso.php" class="nav-link"><i class="bi bi-star"></i>ISO</a></li>
    <li><a href="9Aff.php" class="nav-link"><i class="bi bi-hand-thumbs-up"></i>AFFILIATION & APPROVAL</a></li>
    <li><a href="8Mm.php" class="nav-link"><i class="bi bi-clock-history"></i>MEETING MINUTES</a></li>
    <li><a href="10Aduit.php" class="nav-link"><i class="bi bi-calculator"></i>AUDIT DETAILS</a></li>
    <li><a href="11Strategic.php" class="nav-link"><i class="bi bi-bezier2"></i>STRATEGIC PLAN</a></li>
    <li><a href="12Best.php" class="nav-link"><i class="bi bi-lightbulb"></i>BEST PRACTICES</a></li>
    <li><a href="13Annual.php" class="nav-link"><i class="bi bi-file-earmark-check"></i>ANNUAL REPORT</a></li>
    <li><a href="documents.html" class="nav-link"><i class="bi bi-cloud-arrow-down"></i>DOWNLOAD IQAC
            FILES</a></li>
            <?php
            $baseFolder = 'files/circular';
            
            // Get all subfolders inside `files/circular/`, excluding hidden folders
            $folders = array_filter(glob("$baseFolder/*"), 'is_dir');
            
            // Sort folders by name (assuming they are named by year, e.g., 2023-2024, 2022-2023)
            usort($folders, function ($a, $b) {
                return strcmp($b, $a); // Sort descending (latest folder first)
            });
            
            $latestFolder = !empty($folders) ? $folders[0] : null; // Get the most recent folder
            
            $showBadge = false; // Default: don't show "NEW" badge
            
            if ($latestFolder) {
                $files = glob("$latestFolder/*.{pdf}", GLOB_BRACE); // Get all PDFs in the latest folder
                $showBadge = !empty($files); // Show badge if there are files
            }
            ?>
            
            <li>
                <a href="circulars.php" class="nav-link">
                    <i class="bi bi-pin"></i> CIRCULARS 
                    <?php if ($showBadge): ?>
                        <div class="badge">NEW</div>
                    <?php endif; ?>
                </a>
            </li>
            
    <li><a href="gallery.php" class="nav-link"><i class="bi bi-images"></i></i>GALLERY</a></li>
</ul>