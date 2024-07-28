<?php
include "koneksi.php";
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
            height: 200px;
        }
    </style>

    <title>Home</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <h2 class="fw-bold mb-5">Home</h2>

        <!-- Card Section -->
        <div class="row mb-5">
            <div class="d-flex justify-content-between w-100">
                <!-- Card 1 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Transaksi</h5>
                        <p class="card-text">189</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Status Proses</h5>
                        <p class="card-text">123</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card flex-fill mx-2" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Status Selesai</h5>
                        <p class="card-text">456</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Card -->
        <div class="card mb-5">
            <div class="card-header">
                Grafik Transaksi Per Bulan
            </div>
            <div class="card-body">
                <div class="chart-container d-flex gap-4">
                    <canvas id="transactionsChart"></canvas>
                    <h1>Data lainnya, Tambahin bang dew</h1>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sample data
        const monthlyData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Transaksi Per Bulan',
                data: [150, 200, 180, 220, 0, 270, 230, 210, 190, 260, 280, 300], // Replace with actual data
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Create the chart
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: monthlyData,
            options: {
                responsive: true,
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
