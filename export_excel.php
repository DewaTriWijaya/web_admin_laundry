<?php
require 'vendor/autoload.php'; // Jika menggunakan Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "koneksi.php";

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (empty($start_date) || empty($end_date)) {
    $query = "SELECT * FROM nota";
} else {
    $query = "SELECT * FROM nota WHERE tgl_masuk BETWEEN '$start_date' AND '$end_date'";
}

$result = $conn->query($query);

if (!$result) {
    die("Error: " . $conn->error);
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No. Nota');
$sheet->setCellValue('B1', 'Total Harga');
$sheet->setCellValue('C1', 'Tanggal');

$row = 2;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $data['no_nota']);
    $sheet->setCellValue('B' . $row, $data['harga_total_bayar']);
    $sheet->setCellValue('C' . $row, date('m/d/y', strtotime($data['tgl_masuk'])));
    $row++;
}

$writer = new Xlsx($spreadsheet);
$fileName = "Laporan_Laundry_" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
?>
