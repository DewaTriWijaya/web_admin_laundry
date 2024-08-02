<?php
require 'vendor/autoload.php'; // Jika menggunakan Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "koneksi.php";

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (empty($start_date) || empty($end_date)) {
    $query = "SELECT 
        t.no_nota, 
        t.no_hp, 
        p.nama, 
        t.berat_cucian, 
        t.harga_total_bayar, 
        t.tgl_masuk, 
        t.estimasi_selesai, 
        t.jenis_pembayaran, 
        s.status_laundry 
        FROM nota t
        JOIN pelanggan p on t.no_hp = p.no_hp
        JOIN statuslaundry s ON t.no_nota = s.no_nota";
} else {
    $query = "SELECT
        t.no_nota, 
        t.no_hp, 
        p.nama, 
        t.berat_cucian, 
        t.harga_total_bayar, 
        t.tgl_masuk, 
        t.estimasi_selesai, 
        t.jenis_pembayaran, 
        s.status_laundry  FROM nota t
        JOIN pelanggan p on t.no_hp = p.no_hp
        JOIN statuslaundry s ON t.no_nota = s.no_nota
        WHERE Tgl_masuk BETWEEN '$start_date' AND '$end_date'";
}

$result = $conn->query($query);

if (!$result) {
    die("Error: " . $conn->error);
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No. Nota');
$sheet->setCellValue('B1', 'No HP');
$sheet->setCellValue('C1', 'Nama');
$sheet->setCellValue('D1', 'Berat Cucian');
$sheet->setCellValue('E1', 'Total Harga');
$sheet->setCellValue('F1', 'Tanggal Masuk');
$sheet->setCellValue('G1', 'Estimasi Selesai');
$sheet->setCellValue('H1', 'Jenis Pembayaran');
$sheet->setCellValue('I1', 'Status Laundry');

$row = 2;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $data['no_nota']);
    $sheet->setCellValue('B' . $row, $data['no_hp']);
    $sheet->setCellValue('C' . $row, $data['nama']);
    $sheet->setCellValue('D' . $row, $data['berat_cucian']);
    $sheet->setCellValue('E' . $row, $data['harga_total_bayar']);
    $sheet->setCellValue('F' . $row, date('m/d/y', strtotime($data['tgl_masuk'])));
    $sheet->setCellValue('G' . $row, date('m/d/y', strtotime($data['estimasi_selesai'])));
    $sheet->setCellValue('H' . $row, $data['jenis_pembayaran']);
    $sheet->setCellValue('I' . $row, $data['status_laundry']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$fileName = "Laporan_Laundry_" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
echo "<pre>";
while ($data = mysqli_fetch_assoc($result)) {
    print_r($data);  // Menampilkan data untuk debugging
}
echo "</pre>";
exit();
?>
