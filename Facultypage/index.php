<?php
function getFilesByExtension($dir, $extensions)
{
    $files = [];
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($ext, $extensions)) {
                        $files[] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }

    // Sort the files numerically using natural order sorting (natsort)
    natsort($files);
    return $files;
}

$pdfFiles = getFilesByExtension('iqacpdf_files/', ['pdf']);
$docFiles = getFilesByExtension('iqacpdf_files/', ['doc', 'docx', 'xlsx']);
$xlFiles = getFilesByExtension('iqacpdf_files/copo', ['xlsx']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>IQAC Files Dashboard</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="top">
        <h1> Kongu Engineering College </h1>
        <a href="./check/index1.php">
            <h1> Internal Quality Assurance CELL (IQAC)</h1>
            <h2 style="color:#DE3163;">
                <center> IQAC Documents Format</center>
            </h2>
    </div>

    <div class="bottom">
        <!--<a href="index.php">Back to Dashboard</a>-->
    </div>

    <div class="container">
        <div class="box1">
            <h2>Quality Plan</h2>
            <li> <a href="iqacpdf_files/qualityplan/00_IQAC_PLAN_01.09.2023.pdf" target="_blank"
                    rel="noopener noreferrer">Download Quality Plan</a></li>

        </div>

        <div class="box1">
            <h2>Roles and Responsibilities</h2>
            <li> <a href="iqacpdf_files/roles/Roles 19.09.24.pdf" target="_blank" rel="noopener noreferrer">Roles</a>
            </li>

        </div>

        <div class="box1">
            <h2>CO-PO ATTAINMENT SHEET</h2>
            <ul>
                <?php foreach ($xlFiles as $file): ?>
                <li><a href="iqacpdf_files/copo/<?php echo $file; ?>" target="_blank"
                        rel="noopener noreferrer"><?php echo $file; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="box2">
            <h2>Download PDF Files</h2>
            <ul>
                <?php foreach ($pdfFiles as $file): ?>
                <li><a href="iqacpdf_files/<?php echo $file; ?>" target="_blank"
                        rel="noopener noreferrer"><?php echo $file; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="box3">
            <h2>Download DOC Files</h2>
            <ul>
                <?php foreach ($docFiles as $file): ?>
                <li><a href="iqacpdf_files/<?php echo $file; ?>" target="_blank"
                        rel="noopener noreferrer"><?php echo $file; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>