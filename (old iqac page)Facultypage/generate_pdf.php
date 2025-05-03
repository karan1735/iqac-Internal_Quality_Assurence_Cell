<?php
session_start();
include("php/config.php");

// Check if department ID is provided
if (isset($_GET['department_id'])) {
    // Fetch data based on the selected department
    $departmentId = $_GET['department_id'];

    // Fetch user IDs associated with the selected department
    $userQuery = mysqli_query($con, "SELECT user_id FROM users WHERE department_id = $departmentId AND user_type = 'staff'");
    $userIds = [];
    while ($row = mysqli_fetch_assoc($userQuery)) {
        $userIds[] = $row['user_id'];
    }

    $departmentNameQuery = mysqli_query($con, "SELECT department_name FROM departments WHERE department_id = $departmentId");
    $departmentName = mysqli_fetch_assoc($departmentNameQuery)['department_name'];


    // Generate PDF content
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Department Data PDF');
    $pdf->SetHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage('L'); // Set orientation to landscape
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->MultiCell(0, 10, 'Kongu Engineering College', 0, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->MultiCell(0, 10, 'Internal Quality Assurance Cell', 0, 'C');
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->MultiCell(0, 10, 'Department Name: ' . $departmentName, 0, 'C');
    $pdf->Ln(2); // Add some space before the table


    // Initialize row counter
    $rowCount = 0;

    // Fetch criteria data
    $criteriaQuery = mysqli_query($con, "SELECT criterion_id, criterion_name FROM criteria");
    while ($criterion = mysqli_fetch_assoc($criteriaQuery)) {
        $pdf->MultiCell(0, 10, '', 0, 'L'); // Adjust top margin as needed

        // Set font size and style for criteria header
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell(0, 10, 'Criterion: ' . $criterion['criterion_name'], 0, 'L');

        // Reset font size and style for table headers and data
        $pdf->SetFont('helvetica', 'B', 9);

        // Add table headers with background color
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(75, 10, 'Indicator Name', 1, 0, 'C', 1);
        $pdf->Cell(75, 10, 'Measurement', 1, 0, 'C', 1);
        $pdf->Cell(36, 10, 'Acceptance', 1, 0, 'C', 1);
        $pdf->Cell(15, 10, 'Expected', 1, 0, 'C', 1);
        $pdf->Cell(15, 10, 'Actual', 1, 0, 'C', 1);
        $pdf->Cell(18, 10, 'Percentage', 1, 0, 'C', 1);
        $pdf->Cell(16, 10, 'Deviation', 1, 0, 'C', 1);
        $pdf->Cell(16, 10, 'Satisfied', 1, 1, 'C', 1);
        $pdf->SetFont('helvetica', '', 8);

        // Fetch data for the current criterion
        foreach ($userIds as $userId) {
            $query = "SELECT i.indicator_name, m.measurement_name, d.acceptance, d.expected, d.actual, d.percentage, d.deviation, d.satisfied
                      FROM indicators i
                      LEFT JOIN measurements m ON i.indicator_id = m.indicator_id
                      LEFT JOIN data d ON i.indicator_id = d.measurement_id
                      WHERE i.criterion_id = {$criterion['criterion_id']} AND d.user_id = $userId";

            $result = mysqli_query($con, $query);

            // Add table data
            while ($row = mysqli_fetch_assoc($result)) {
                // Increment row counter
                $rowCount++;

                // Check if adding the current row will exceed the remaining space on the page
                if ($pdf->getY() + 12 > ($pdf->getPageHeight() - PDF_MARGIN_BOTTOM)) {
                    $pdf->AddPage('L'); // Add a new page
                    $pdf->MultiCell(75, 12, $row['indicator_name'], 1, 'C', 0, 0, '', '', true, 0, false, true, 12, 'M');
                    $pdf->MultiCell(75, 12, $row['measurement_name'], 1, 'C', 0, 0, '', '', true, 0, false, true, 12, 'M');
                    $pdf->Cell(36, 12, $row['acceptance'], 1, 0, 'C');
                    $pdf->Cell(15, 12, $row['expected'], 1, 0, 'C');
                    $pdf->Cell(15, 12, $row['actual'], 1, 0, 'C');
                    $pdf->Cell(18, 12, $row['percentage'], 1, 0, 'C');
                    $pdf->Cell(16, 12, $row['deviation'], 1, 0, 'C');
                    $pdf->Cell(16, 12, $row['satisfied'], 1, 1, 'C');
                    $rowCount = 0; // Reset the row counter
                } else {
                    $pdf->MultiCell(75, 12, $row['indicator_name'], 1, 'C', 0, 0, '', '', true, 0, false, true, 12, 'M');
                    $pdf->MultiCell(75, 12, $row['measurement_name'], 1, 'C', 0, 0, '', '', true, 0, false, true, 12, 'M');
                    $pdf->Cell(36, 12, $row['acceptance'], 1, 0, 'C');
                    $pdf->Cell(15, 12, $row['expected'], 1, 0, 'C');
                    $pdf->Cell(15, 12, $row['actual'], 1, 0, 'C');
                    $pdf->Cell(18, 12, $row['percentage'], 1, 0, 'C');
                    $pdf->Cell(16, 12, $row['deviation'], 1, 0, 'C');
                    $pdf->Cell(16, 12, $row['satisfied'], 1, 1, 'C');
                }
            }
        }
    }
    //last page content
    $pdf->Ln(30); // Add some space before the table
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Signature of Coordinator', 0, 0, 'L');
    $pdf->Cell(0, 10, 'Signature of HOD', 0, 1, 'R');


    // Output the PDF
    $pdf->Output($departmentName . '_iqaclist.pdf', 'D'); // Output file name based on department name
} else {
    echo "Department ID not provided.";
}
?>
