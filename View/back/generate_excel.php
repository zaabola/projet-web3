<?php
// Include PHPExcel library
require_once 'PHPExcel-1.8.2/Classes/PHPExcel.php';

// Include your user controller
include_once '../../Controller/userC.php';
include_once '../../config.php';

// Fetch users from the database
$userController = new userC();
$users = $userController->listUsers();

// Create a new PHPExcel object
$excel = new PHPExcel();
$sheet = $excel->setActiveSheetIndex(0);

// Set sheet title
$sheet->setTitle('User List');

if (empty($users)) {
    echo "No users found.";
    exit;}

// Add headers to the Excel file
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Prénom');
$sheet->setCellValue('D1', 'Adresse');
$sheet->setCellValue('E1', 'Téléphone');
$sheet->setCellValue('F1', 'Email');
$sheet->setCellValue('G1', 'Rôle');
$sheet->setCellValue('H1', 'Nationalité');




// Populate data from the `$users` array
$row = 2; // Data starts from the second row
foreach ($users as $user) {
    // Check if 'nationalite' exists in the $user array, if not set it to 'Unknown'
    $nationalite = isset($user['nationalite']) ? $user['nationalite'] : 'Unknown';
    
    $sheet->setCellValue('A' . $row, $user['id']);
    $sheet->setCellValue('B' . $row, $user['nom']);
    $sheet->setCellValue('C' . $row, $user['prenom']);
    $sheet->setCellValue('D' . $row, $user['adresse']);
    $sheet->setCellValue('E' . $row, $user['telephone']);
    $sheet->setCellValue('F' . $row, $user['email']);
    $sheet->setCellValue('G' . $row, $user['role']);
    $sheet->setCellValue('H' . $row, $nationalite); // Set the nationalite value here
    $row++;
}


/*
// Set headers to download the Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Liste_Utilisateurs.xls"');
header('Cache-Control: max-age=0');

// Write the Excel file
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');*/
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Liste_Utilisateurs.xls"');
header('Cache-Control: max-age=0');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');

exit;
?>
