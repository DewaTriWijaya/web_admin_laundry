<?php
include "koneksi.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to count total number of nota
$sql_total = "SELECT COUNT(*) AS total_nota FROM nota";
$result_total = mysqli_query($conn, $sql_total);

$total_nota = 0; // Initialize variable

if ($result_total) {
    $row = mysqli_fetch_assoc($result_total);
    $total_nota = $row['total_nota'];
} else {
    echo "Error: " . mysqli_error($conn);
}

// SQL query to count unfinished laundry
$sql_unfinished = "SELECT COUNT(*) AS status_belum FROM status_laundry WHERE status_laundry = 'Belum'";
$result_unfinished = mysqli_query($conn, $sql_unfinished);

$total_unfinished = 0; // Initialize variable

if ($result_unfinished) {
    $row = mysqli_fetch_assoc($result_unfinished);
    $total_unfinished = $row['status_belum'];
} else {
    echo "Error: " . mysqli_error($conn);
}

// SQL query to count finished laundry
$sql_finished = "SELECT COUNT(*) AS status_selesai FROM status_laundry WHERE status_laundry = 'Selesai'";
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
        DATE_FORMAT(Tgl_masuk, '%Y-%m') AS month, 
        COUNT(*) AS transaction_count 
    FROM 
        nota 
    GROUP BY 
        DATE_FORMAT(Tgl_masuk, '%Y-%m')
    ORDER BY 
        DATE_FORMAT(Tgl_masuk, '%Y-%m')
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

mysqli_close($conn);
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
            height: 100% !important; /* Ensures the canvas height adjusts to the container */
        }
    </style>

    <title>Transaksi</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <h2 class="fw-bold mb-5">Transaksi</h2>

        <!-- Card Section -->
        <div class="row mb-5">
            <div class="d-flex justify-content-between w-100">
                <!-- Card 1 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Transaksi</h5>
                        <p class="card-text"><?php echo $total_nota; ?></p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Status Belum Selesai</h5>
                        <p class="card-text"><?php echo $total_unfinished; ?></p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Status Selesai</h5>
                        <p class="card-text"><?php echo $total_finished; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Card -->
        <div class="card mb-3">
            <div class="card-header">
                Grafik Transaksi Per Bulan
            </div>
            <div class="card-body">
                <div class="chart-container d-flex gap-2">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get data from PHP
        const months = <?php echo json_encode($months); ?>;
        const transactionCounts = <?php echo json_encode($transaction_counts); ?>;

        // Create the chart
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
    </script>
</body>

</html>
