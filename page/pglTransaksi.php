<?php
include "koneksi.php";

$showModal = false;
$jenisCucianOptions = '';
$tableRows = '';

// Ambil data jenis cucian dari database untuk dropdown
$result = $conn->query("SELECT id_jenis_cucian, jenis_cucian, harga FROM jenis_cucian");
while ($row = $result->fetch_assoc()) {
    $jenisCucianOptions .= "<option value='{$row['id_jenis_cucian']}' data-harga='{$row['harga']}'>{$row['jenis_cucian']}</option>";
}

// Inisialisasi data tabel dari sesi jika ada
if (!isset($_SESSION['table_data'])) {
    $_SESSION['table_data'] = [];
}

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tambah_jenis_cucian'])) {
        $showModal = true;
    } elseif (isset($_POST['simpan_jenis_cucian'])) {
        $jenisCucianId = $_POST['jenis_cucian'];
        $jumlah = $_POST['jumlah'];

        // Ambil data jenis cucian dari database
        $stmt = $conn->prepare("SELECT jenis_cucian, harga FROM jenis_cucian WHERE id_jenis_cucian = ?");
        $stmt->bind_param("i", $jenisCucianId);
        $stmt->execute();
        $result = $stmt->get_result();
        $jenisCucian = $result->fetch_assoc();
        
        // Hitung total harga
        $totalHarga = $jenisCucian['harga'] * $jumlah;

        // Tambahkan data ke sesi
        $_SESSION['table_data'][] = [
            'jenis' => $jenisCucian['jenis_cucian'],
            'jumlah' => $jumlah,
            'total_harga' => $totalHarga
        ];
    } elseif (isset($_POST['hapus_tabel'])) {
        $indexToDelete = $_POST['index'];
        // Hapus item dari sesi berdasarkan indeks
        if (isset($_SESSION['table_data'][$indexToDelete])) {
            unset($_SESSION['table_data'][$indexToDelete]);
            // Reindeks array untuk memastikan indeks tetap berurutan
            $_SESSION['table_data'] = array_values($_SESSION['table_data']);
        }
    }
}

// Tampilkan data dari sesi ke tabel HTML
foreach ($_SESSION['table_data'] as $index => $data) {
    $tableRows .= "<tr>
                       <th scope='row'>" . ($index + 1) . "</th>
                       <td>{$data['jenis']}</td>
                       <td>{$data['jumlah']}</td>
                       <td>Rp {$data['total_harga']}</td>
                       <td>
                           <form method='post'>
                               <input type='hidden' name='index' value='{$index}'>
                               <button class='btn btn-outline-danger' type='submit' name='hapus_tabel'>
                                   <i class='bi bi-trash'></i>
                               </button>
                           </form>
                       </td>
                   </tr>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Status Laundry</title>
</head>
<body class="bg-secondary" style="--bs-bg-opacity:.15;">
<form method="POST" action="">
    <div class="container">
        <div class="row mb-3">

            <div class="head mb-5 mt-1 ">
                <h2 class="fw-bold">Pengelolaan Transaksi</h2>
            </div>
            
            <div class="d-flex justify-content-start mb-3">
                <div class="col">
                    <input type="text" class="form-control" name="no_handphone" placeholder="Masukan No Handphone Pelanggan">
                </div>
                <div class="col">
                    <button class="btn btn btn-outline-dark" type="submit" name="cari_pelanggan"><i class="bi bi-search"></i> Cari</button>
                </div>   
            </div>
            <div class="d-flex flex-row">
                <div class="row align-self-center me-1">
                    <h5><b>Jenis Cucian</b></h5>  
                </div>
                <div class="row p-2">
                    <button class="btn btn-sm" type="submit" name="tambah_jenis_cucian"><h3><i class="bi bi-plus-circle"></i></h3></button>
                </div>
            </div>
            <div class="d-flex justify-content-start">
                <table class="table mt-2 w-75">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Jenis Cucian</th>
                            <th scope="col">Jumlah Kilogram</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        <?= $tableRows; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col">
            <div class="row mb-4">
                <div class="col me-4">
                    <h6><b>Tanggal Transaksi</b></h6>
                    <div class="col-md-7">
                        <input type="date" class="form-control" name="tanggal_transaksi">
                    </div>
                </div>
                <div class="col me-4">
                    <h6><b>Total Harga</b></h6>
                    <div class="col-md-5">
                        <fieldset disabled>
                            <input type="text" id="disabledTextInput" name="totah_harga" class="form-control bg-light">
                        </fieldset >    
                    </div>         
                </div>
            </div>

            <div class="row mb-5">
                <div class="col me-4">
                    <h6><b>Estimasi Selesai</b></h6>
                    <div class="col-md-7">
                        <input type="date" class="form-control" name="Estimasi_selesai">
                    </div>
                </div>
                <div class="col me-4">
                    <h6><b>Pembayaran<b></h6>
                    <div class="d-flex flex-row mb-3">
                        <div class="row me-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="qris" id="flexRadioDefault1">
                                <p>QRIS</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tunai" id="flexRadioDefault1">
                                <p>Tunai</p>
                            </div>
                        </div>
                    </div>         
                </div>
            </div>
        </div>

        <div class="d-flex flex-row mb-3">
            <div class="row me-5">
                <button type="submit" class="btn btn-success p-2 px-4" name="simpan">Simpan</button>
            </div>
            <div class="row ms-3">
                <button type="button" class="btn btn-danger p-2 px-4">Hapus</button>
            </div>
        </div>
    </div>
</form>
<?php if ($showModal): ?>
<!-- Pop-up Dialog -->
<div class="modal fade show" style="display:block;" tabindex="-1" role="dialog" aria-labelledby="jenisCucianModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisCucianModalLabel">Tambah Jenis Cucian</h5>
                <form method="POST" action="">
                    <button type="submit" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </form>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="jenis_cucian">Jenis Cucian:</label>
                        <select class="form-control" id="jenis_cucian" name="jenis_cucian" required>
                            <option value="">Pilih Jenis Cucian</option>
                            <?= $jenisCucianOptions; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah Per-Kilogram:</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Perkiraan Harga:</label>
                        <input type="text" class="form-control" id="harga" name="harga" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary" name="simpan_jenis_cucian">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
</body>
</html>
