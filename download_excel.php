<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "cerdas_finansial");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$query = $conn->prepare("SELECT tipe, jumlah, deskripsi, tanggal FROM transaksi WHERE username = ? ORDER BY tanggal DESC");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Transaksi');

$sheet->setCellValue('A1', 'Tipe');
$sheet->setCellValue('B1', 'Jumlah');
$sheet->setCellValue('C1', 'Deskripsi');
$sheet->setCellValue('D1', 'Tanggal');

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowIndex", ucfirst($row['tipe']));
    $sheet->setCellValue("B$rowIndex", $row['jumlah']);
    $sheet->setCellValue("C$rowIndex", $row['deskripsi']);
    $sheet->setCellValue("D$rowIndex", date('d-m-Y H:i', strtotime($row['tanggal'])));
    $rowIndex++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_Transaksi_$username.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;