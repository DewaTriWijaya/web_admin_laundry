<?php
include "koneksi.php";
$result = null; // Inisialisasi variabel $result

if (isset($_POST['cari'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date']; 

    if (empty($start_date) || empty($end_date)) {
        // Query untuk mengambil semua data transaksi jika tidak ada input tanggal
        $query = "SELECT * FROM Nota";
    } else {
        // Query untuk mengambil data transaksi berdasarkan jangka waktu
        $query = "SELECT * FROM Nota WHERE Tgl_masuk BETWEEN '$start_date' AND '$end_date'";
    }

    $result = $conn->query($query);

    if (!$result) {
        echo "Error: " . $conn->error;
    }
} else {
    // Query untuk mengambil semua data transaksi
    $query = "SELECT * FROM Nota";
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
    <style>
         .table-wrapper {
            overflow-x: auto;
        }

        .table-fixed-header {
            height: 290px;
            overflow-y: auto;
        }

        .table-fixed-header thead th {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }
        .home-uhuy {
            margin-left: 300px;
        }

    </style>
    <title>Laporan</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
<div class="container vh-100 home-uhuy">
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
        <div class="">
            <div class="table-wrapper card">
                <div class="table-fixed-header">      
            <table class="table table-bordered table-striped">
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
    </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-success px-4" onclick="downloadExcel()">Unduh</button>
        </div>
    </div>
</div>

<script>
    function downloadExcel() {
        const start_date = document.querySelector('input[name="start_date"]').value;
        const end_date = document.querySelector('input[name="end_date"]').value;
        let url = 'export_excel.php';

        if (start_date && end_date) {
            url += `?start_date=${start_date}&end_date=${end_date}`;
        }

        window.location.href = url;
    }
</script>
</body>
</html>