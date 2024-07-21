<?php
include "koneksi.php";

$result = null; // Inisialisasi variabel $result

if (isset($_POST['cari'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date']; 

    if (empty($start_date) || empty($end_date)) {
        // Query untuk mengambil semua data transaksi jika tidak ada input tanggal
        $query = "SELECT * FROM nota";
    } else {
        // Query untuk mengambil data transaksi berdasarkan jangka waktu
        $query = "SELECT * FROM nota WHERE Tgl_masuk BETWEEN '$start_date' AND '$end_date'";
    }

    $result = $conn->query($query);

    if (!$result) {
        echo "Error: " . $conn->error;
    }
} else {
    // Query untuk mengambil semua data transaksi
    $query = "SELECT * FROM nota";
    $result = $conn->query($query);

    if (!$result) {
        echo "Error: " . $conn->error;
    }
}

$conn->close(); // Menutup koneksi setelah selesai
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
<div class="container">
    <div class="row">
        <h2 class="fw-bold mb-5">Laporan Laundry</h2>
        <h4 class="mb-4">Masukkan Jangka Waktu</h4>
        <form method="POST" action="" class="d-flex gap-4 mb-5">
            <div class="d-flex gap-4 ">
                <input type="date" name="start_date" class="form-control">
                <input type="date" name="end_date" class="form-control">
            </div>
            <button type="submit" name="cari" class="btn btn-success px-4">Cari</button>
        </form>

        <h4>Transaksi</h4>
        <div class="d-flex justify-content-center">
            <table class="table table-borderless">
                <thead class="text-center">
                    <tr>
                        <th scope="col">No. Nota</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    <?php
                    if (isset($result)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='text-center'>{$row['No_Nota']}</td>";
                            echo "<td class='text-center'>Rp. " . number_format($row['Total_Harga'], 0, ',', '.') . "</td>";
                            echo "<td class='text-center'>" . date('m/d/y', strtotime($row['Tgl_masuk'])) . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-start mt-4">
            <button type="button" class="btn btn-success px-4">Unduh</button>
        </div>
    </div>
</div>
</body>
</html>