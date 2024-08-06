<?php
include "../koneksi.php";
$result = null; // Inisialisasi variabel $result

// Mencari Data
if (isset($_POST['cari'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date']; 

    if (empty($start_date) || empty($end_date)) {
        // Query untuk mengambil semua data transaksi jika tidak ada input tanggal
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
    } elseif (!empty($start_date) || !empty($end_date)) {
        // Query untuk mengambil data transaksi berdasarkan jangka waktu
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

    if ($result) {
        $data_count = $result->num_rows;
    } else {
        $data_count = 0;
    }

    // Trigger
    if ($data_count === 0) {
        echo '
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById("transaksiModal"));
            myModal.show();
            });
        </script>';
    }

    if (!$result) {
        echo "Error: " . $conn->error;
    }
} else {
    // Query untuk mengambil semua data transaksi
    $sql = "SELECT 
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
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error: " . $conn->error;
    }
}
// Akhir Mencari Data

// SQL query to count total number of nota
$sql_total = "SELECT COUNT(*) AS total_nota FROM nota";
$result_total = mysqli_query($conn, $sql_total);
$total_nota = 0;

if ($result_total) {
    $row = mysqli_fetch_assoc($result_total);
    $total_nota = $row['total_nota'];
} else {
    echo "Error: " . mysqli_error($conn);
}
// Akhir SQL query to count total number of nota


// SQL query to count unfinished laundry
$sql_unfinished = "SELECT COUNT(*) AS status_belum FROM statuslaundry WHERE status_laundry = 'Belum'";
$result_unfinished = mysqli_query($conn, $sql_unfinished);

$total_unfinished = 0;

if ($result_unfinished) {
    $row = mysqli_fetch_assoc($result_unfinished);
    $total_unfinished = $row['status_belum'];
} else {
    echo "Error: " . mysqli_error($conn);
}
// Akhir SQL query to count unfinished laundry


// SQL query to count finished laundry
$sql_finished = "SELECT COUNT(*) AS status_selesai FROM statuslaundry WHERE status_laundry = 'Selesai'";
$result_finished = mysqli_query($conn, $sql_finished);

$total_finished = 0; // Initialize variable

if ($result_finished) {
    $row = mysqli_fetch_assoc($result_finished);
    $total_finished = $row['status_selesai'];
} else {
    echo "Error: " . mysqli_error($conn);
}
// Akhir SQL query to count finished laundry

// SQL query to get transaction counts per month
$sql_transactions_per_month = "
    SELECT 
        DATE_FORMAT(tgl_masuk, '%Y-%m') AS month, 
        COUNT(*) AS transaction_count 
    FROM 
        nota 
    GROUP BY 
        DATE_FORMAT(tgl_masuk, '%Y-%m')
    ORDER BY 
        DATE_FORMAT(tgl_masuk, '%Y-%m')
";
$result_transactions_per_month = mysqli_query($conn, $sql_transactions_per_month);

$transaction_counts = [];
$months = [];

if ($result_transactions_per_month) {
    while ($row = mysqli_fetch_assoc($result_transactions_per_month)) {
        $months[] = $row['month'];
        $transaction_counts[] = $row['transaction_count'];
    }
} else {
    echo "";
}
// Akhir SQL query to get transaction counts per month

 // Fetch data for pie chart
 $sql_chart = "SELECT 
 SUM(CASE WHEN status_laundry = 'Selesai' THEN 1 ELSE 0 END) AS Selesai, 
 SUM(CASE WHEN status_laundry != 'Selesai' THEN 1 ELSE 0 END) AS Belum
     FROM statuslaundry;";
 $result_chart = $conn->query($sql_chart);
 $chart_data = $result_chart->fetch_assoc();
  // Akhir Fetch data for pie chart
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="page/laporan/laporan.css">
    <link rel="stylesheet" href="laporan/laporan.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
        }

         .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 25px;
            font-weight: bold;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        body {
            font-family: poppins;
        }
        .sidebar {
            width: 380px;
        }

        .bg-active {
            background-color: #0086ac;
        }

    </style>
    <title>Laporan</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
<!-- awal -->
<div class="d-flex">
        <div class="p-4 bg-custom sidebar">
            <div class="d-flex gap-4">
                <img src="../img/Logo.png" alt="logo" class="w-25 h-25">
                <div class="text-white align-self-center">
                    <h1 class="fs-5">Admin</h1>
                    <p class="lh-1">Sistem Laundry</p>
                </div>
            </div>
            <div class="mt-5" >
                <div class="side-menu" onclick="window.location.href='../index.php'">
                    <img src="../img/user.png" alt="pendaftaran">
                    <a class="text-white" href="index.php" style="text-decoration:none">Pendaftaran</a>
                </div>

                <div class="side-menu" onclick="window.location.href='pglTransaksi.php'">
                    <img src="../img/tag.png" alt="transaksi">
                    <a class="text-white" href="pglTransaksi.php" style="text-decoration:none">Pengelolaan Transaksi</a>
                </div>

                <div class="side-menu" onclick="window.location.href='pglCucian.php'">
                    <img src="../img/washing.png" alt="cucian">
                    <a class="text-white" href="pglCucian.php" style="text-decoration:none">Pengelolaan Cucian</a>
                </div>

                <div class="side-menu bg-active" onclick="window.location.href='laporan.php'">
                    <img src="../img/bar-chart.png" alt="laporan">
                    <a class="text-white" href="laporan.php" style="text-decoration:none">Laporan</a>
                </div>

                <div class="side-menu" onclick="window.location.href='statusLaundry.php'">
                    <img src="../img/bell.png" alt="status">
                    <a class="text-white" href="statusLaundry.php" style="text-decoration:none">Status Laundry</a>
                </div>
            </div>
        </div>

        <div class="p-3 d-flex w-100">
<!-- akhir -->
<div class="container p-4">
    <div class="row resf">
        <h2 class="fw-bold mb-5">Laporan Laundry</h2>

    <!-- Card Section -->
    <div class="mb-3">
            <div class="d-flex justify-content-between gap-4">
                <!-- Card 1 -->
                <div class="card flex-fill" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-wallet2 card-title"> Transaksi</h5>
                        <p class="card-text"><?php echo $total_nota; ?></p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card flex-fill" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-hourglass-split card-title"> Status Belum</h5>
                        <p class="card-text"><?php echo $total_unfinished; ?></p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card flex-fill" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-check-circle card-title"> Status Selesai</h5>
                        <p class="card-text"><?php echo $total_finished; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-2">
            <div class="card-container-between">

                <!-- Chart Card Transaksi -->
                <div class="card full-width">
                    <div class="card-header">
                        Transaksi Per-Bulan
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="transactionsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Card Status -->
                <div class="card fixed-width">
                    <div class="card-header">
                        Transaksi Selesai / Belum
                    </div>
                    <div class="card-body">
                        <div class="chart-container-uhuy">
                            <div class="chart-item-uhuy">
                                <canvas id="doughnutChart"></canvas>
                                <div class="chart-text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <div class="d-flex flex-column align-items-center">
        <h4 class="mb-4 mt-5">Masukkan Jangka Waktu</h4>
        <form method="POST" action="" class="d-flex gap-4 mb-2">
            <div class="d-flex gap-4 ">
                <input type="date" name="start_date" class="form-control">
                <input type="date" name="end_date" class="form-control">
            </div>
            <button type="submit" name="cari" class="btn btn-success px-4">Cari</button>
        </form>
    </div>

        <h4>Transaksi</h4>
        <div class="">
            <div class="table-wrapper card">
                <div class="table-fixed-header">      
            <table class="table table-bordered table-striped">
                <thead class="text-center fs-6">
                    <tr>
                        <th scope="col">No. Nota</th>
                        <th scope="col">No HP</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Berat Cucian</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Tanggal Masuk</th>
                        <th scope="col">Estimasi Selesai</th>
                        <th scope="col">Jenis Pembayaran</th>
                        <th scope="col">Status Laundry</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    <?php
                    if (isset($result)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='text-center fs-6'>{$row['no_nota']}</td>";
                            echo "<td class='text-center fs-6'>{$row['no_hp']}</td>";
                            echo "<td class='text-center fs-6'>{$row['nama']}</td>";
                            echo "<td class='text-center fs-6'>{$row['berat_cucian']}</td>";
                            echo "<td class='text-center fs-6'>Rp. " . number_format($row['harga_total_bayar'], 0, ',', '.') . "</td>";
                            echo "<td class='text-center fs-6'>" . date('m/d/y', strtotime($row['tgl_masuk'])) . "</td>";
                            echo "<td class='text-center fs-6'>" . date('m/d/y', strtotime($row['estimasi_selesai'])) . "</td>";
                            echo "<td class='text-center fs-6'>{$row['jenis_pembayaran']}</td>";
                            echo "<td class='text-center fs-6'>{$row['status_laundry']}</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>
        <div class="d-flex justify-content-end mt-4 mb-5">
            <button type="button" class="btn btn-success px-4" onclick="downloadExcel()">Unduh</button>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <span class="bi bi-info-circle modal-title"> Pemberitahuan</span>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-center">Tidak ada transaksi pada periode yang dipilih</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Ya</button>
      </div>
    </div>
  </div>
</div> -->
<!-- Akhir -->

<div id="transaksiModal" class="modal">
        <div class="modal-dialog modal-dialog-centered">                           
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <span class="bi bi-info-circle modal-title"> Pemberitahuan</span>
                </div>
                <p class="text-center">Tidak ada transaksi pada <br>periode yang dipilih</p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Ya</button>
                </div>
            </div>
        </div>
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



    const months = <?php echo json_encode($months); ?>;
        const transactionCounts = <?php echo json_encode($transaction_counts); ?>;

        // Create the chart Bar
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Transaksi Per Bulan',
                    data: transactionCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Create the chart Pie
        var ctx12 = document.getElementById('doughnutChart').getContext('2d');
        var totalAktivitas = <?php echo $chart_data['Selesai'] + $chart_data['Belum']; ?>;
        var persentaseBerhasil = Math.round((<?php echo $chart_data['Selesai']; ?> / totalAktivitas) * 100);

        var doughnutChart = new Chart(ctx12, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Belum'],
                datasets: [{
                    data: [<?php echo $chart_data['Selesai']; ?>, <?php echo $chart_data['Belum']; ?>],
                    backgroundColor: ['#36a2eb', '#ff6384'],
                    hoverBackgroundColor: ['#36a2eb', '#ff6384']
                }]
            },
            options: {
                responsive: true,
                cutoutPercentage: 70,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                var label = tooltipItem.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += Math.round(tooltipItem.raw * 100 / totalAktivitas) + '%';
                                return label;
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('percentage').textContent = persentaseBerhasil + '%';
        document.getElementById('summary').textContent = '<?php echo $chart_data['Selesai']; ?> dari ' + totalAktivitas + ' total Transaksi';


</script>
</body>
</html>