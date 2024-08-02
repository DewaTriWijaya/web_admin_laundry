function downloadExcel() {
    console.log("Halo jalan")
// const start_date = document.querySelector('input[name="start_date"]').value;
// const end_date = document.querySelector('input[name="end_date"]').value;
// let url = 'export_excel.php';

// if (start_date && end_date) {
//     url += `?start_date=${start_date}&end_date=${end_date}`;
// }

// window.location.href = url;
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

