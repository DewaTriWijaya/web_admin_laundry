<?php
// include "koneksi.php"; // Jika Anda tidak menggunakan file ini, hapus atau uncomment sesuai kebutuhan
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class="d-flex vh-100">
        <div class="p-4 bg-custom">
            <div class="d-flex gap-4 align-items-center">
                <img src="img/Logo.png" alt="logo" class="w-25 h-25">
                <div class="text-white align-self-center">
                <h1>Admin</h1>
                <p class="lh-1">Sistem Laundry</p>
                </div>
            </div>
            <div class="mt-5" >
                <div class="side-menu" onclick="window.location.href='index.php?page=home'">
                    <img src="img/dashboard.png" alt="beranda">
                    <a class="text-white" href="index.php?page=home">Beranda</a>
                </div>
                <div class="side-menu" onclick="window.location.href='index.php?page=pendaftaran'">
                    <img src="img/user.png" alt="pendaftaran">
                    <a class="text-white" href="index.php?page=pendaftaran">Pendaftaran</a>
                </div>

                <div class="side-menu d-flex align-items-center" onclick="document.querySelector('#transaksi-link').click()">
                    <img src="img/transaksi.png" alt="transaksi" class="me-2">
                    <a id="transaksi-link" data-bs-toggle="collapse" href="#transaksiEx" role="button" aria-expanded="false" aria-controls="transaksiEx" class="text-white">Transaksi</a>
                </div>
                    <div class="collapse" id="transaksiEx">
                        <div class="submenu">
                            <div class="side-menu" onclick="window.location.href='index.php?page=pendaftaran'">
                            <img src="img/notebook.png" alt="pendaftaran">
                            <a class="text-white" href="index.php?page=pglTransaksi">Pengelolaan Transaksi</a>
                        </div>
                            <div class="submenu-item side-menu d-flex align-items-center" onclick="window.location.href='index.php?page=sub2'">
                            <img src="img/washing.png" alt="pendaftaran">
                            <a class="text-white" href="index.php?page=pglCucian">Pengelolaan Cucian</a>
                            </div>
                        </div>
                    </div>
                <div class="side-menu" onclick="window.location.href='index.php?page=laporan'">
                    <img src="img/bar-chart.png" alt="laporan">
                    <a class="text-white" href="index.php?page=laporan">Laporan</a>
                </div>
                <div class="side-menu" onclick="window.location.href='index.php?page=statusLaundry'">
                    <img src="img/bell.png" alt="status">
                    <a class="text-white" href="index.php?page=statusLaundry">Status Laundry</a>
                </div>
            </div>
        </div>

        <div class="p-3">
            <?php
            if (isset($_GET['page'])) {
            $page = $_GET['page'];

            switch ($page) {
            case 'home':
                include 'page/home.php';
                break;
            case 'jenisCucian':
                include 'page/jenisCucian.php';
                break;
            case 'nota':
                include 'page/nota.php';
                break;
            case 'laporan':
                include 'page/laporan.php';
                break;
            case 'pendaftaran':
                include 'page/pendaftaran.php';
                break;
            case 'statusLaundry':
                include 'page/statusLaundry.php';
                break;
            case 'transaksi':
                include 'page/transaksi.php';
                break;
            case 'pglCucian':
                include 'page/pglCucian.php';
                break;
            case 'pglTransaksi':
                include 'page/pglTransaksi.php';
                break;
            default:
                include 'page/home.php';
            }
        } else {

        }
            // Hapus { dan perbaiki penutup <section> dan komentar
        ?>
        </div>
    </div>



<script src="js/bootstrap.min.js"></script>
</body>
</html>