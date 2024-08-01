<?php
include "koneksi.php";


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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
    echo "Error: " . mysqli_error($conn);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .carousel-item {
            min-height: 200px;
        }

        .chart-container {
            position: relative;
            height: 250px;
        }

        #transactionsChart {
            height: 100% !important;
        }

        .chart-item {
            flex: 1;
            min-width: 300px;
            margin: 20px;
        }

        .chart-container-uhuy {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .chart-item-uhuy {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-item-uhuy canvas {
            display: block;
        }

        .chart-item-uhuy .chart-text {
            position: absolute;
            text-align: center;
        }

        .card-container-between {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            /* Optional: adds space between the cards */
        }

        .card-between {
            flex: 1;
            min-width: 300px;
            /* Minimum width to ensure responsiveness */
            margin: 10px;
        }

        /* Flexbox container */
        .card-container-between {
            display: flex;
            gap: 1rem;
        }

        /* Full width card */
        .card.full-width {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Fixed width card */
        .card.fixed-width {
            width: 350px;
        }

        /* Card styling */
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Card header styling */
        .card-header {
            background-color: #f5f5f5;
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        /* Card body styling */
        .card-body {
            padding: 0.75rem;
        }

        /* Chart container styling */
        .chart-container,
        .chart-container-uhuy {
            position: relative;
            height: 100%;
        }

        /* Chart item styling */
        .chart-item-uhuy {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Chart text styling */
        .chart-text {
            position: absolute;
            text-align: center;
        }

        .table td,
        .table th {
            padding: 8px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table-fixed-header {
            height: 250px;
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

    <title>Transaksi</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container vh-100 home-uhuy">
        <h2 class="fw-bold mb-3">Transaksi</h2>

        <!-- Card Section -->
        <div class="row mb-3">
            <div class="d-flex justify-content-between w-100">
                <!-- Card 1 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-wallet2 card-title">Transaksi</h5>
                        <p class="card-text"><?php echo $total_nota; ?></p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-hourglass-split card-title">Status Belum</h5>
                        <p class="card-text"><?php echo $total_unfinished; ?></p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="bi bi-check-circle card-title">Status Selesai</h5>
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

        <!-- Tabel Card -->
        <div class="card mt-2">
            <div class="card-header">
                Transaksi
            </div>
            <div class="table-wrapper card m-4">
            <div class="table-fixed-header">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No Nota</th>
                                <th>No HP</th>
                                <th>Nama</th>
                                <th>Berat Cucian</th>
                                <th>Total Harga</th>
                                <th>Tgl Masuk</th>
                                <th>Estimasi Selesai</th>
                                <th>Jenis Pembayaran</th>
                                <th>Status Laundry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data from database with JOIN
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
                                JOIN pelanggan p
                                JOIN statuslaundry s";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['no_nota']}</td>
                                        <td>{$row['no_hp']}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['berat_cucian']}</td>
                                        <td>{$row['harga_total_bayar']}</td>
                                        <td>{$row['tgl_masuk']}</td>
                                        <td>{$row['estimasi_selesai']}</td>
                                        <td>{$row['jenis_pembayaran']}</td>
                                        <td>{$row['status_laundry']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No data found</td></tr>";
                            }

                            // Fetch data for pie chart
                            $sql_chart = "SELECT 
                            SUM(CASE WHEN status_laundry = 'Selesai' THEN 1 ELSE 0 END) AS Selesai, 
                            SUM(CASE WHEN status_laundry != 'Selesai' THEN 1 ELSE 0 END) AS Belum
                                FROM statuslaundry;";
                            $result_chart = $conn->query($sql_chart);
                            $chart_data = $result_chart->fetch_assoc();

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
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