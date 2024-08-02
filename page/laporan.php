<?php
include "koneksi.php";
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Laporan</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity: .15;">
<div class="container vh-100 home-uhuy">
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
                                    <h2 id="percentage">0%</h2>
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
<div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
       <h1 class="bi bi-info-circle modal-title fs-5" id="exampleModalLabel"> Pemberitahuan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-center">Tidak ada transaksi pada periode yang dipilih</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ya</button>
      </div>
    </div>
  </div>
</div>
<!-- Akhir -->
 

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