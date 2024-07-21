<?php
include "koneksi.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM status_laundry";
$result = mysqli_query($conn, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['No_Nota'] as $index => $no_nota) {
        $tanggal_selesai = $_POST['tanggal_selesai'][$index];
        $sql = "UPDATE status_laundry SET tanggal_selesai='$tanggal_selesai' WHERE No_Nota='$no_nota'";
        mysqli_query($conn, $sql);
    }
    header("Location: statusLaundry.php"); // Refresh halaman setelah update
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Status Laundry</title>
</head>

<body class="bg-secondary" style="--bs-bg-opacity: .15;">
    <div class="container">
        <div class="row">
            <h2 class="fw-bold mb-5">Informasi Status Laundry</h2>
            <div class="justify-content-center">
            <form method="POST" action="statusLaundry.php">
                    <table class="table table-borderless">
                        <thead class="text-center">
                            <tr>
                                <th scope="col">No. Nota</th>
                                <th scope="col">Tanggal Selesai</th>
                                <th scope="col">Status Laundry</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            <?php 
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = $row["status_laundry"];
                                        echo "<tr>";
                                        echo "<td scope='row' class='text-center'><input type='hidden' name='No_Nota[]' value='".$row['No_Nota']."'>".$row['No_Nota']."</td>";
                                        echo "<td><input type='date' class='form-control' name='tanggal_selesai[]' value='".$row['tanggal_selesai']."'></td>";
                                        echo "<td class='text-center'><select name='status'><option name='$status' value='$status'>$status</option></select></td>";
                                        echo "<td><button type='button' class='btn btn-primary'>Pemberitahuan</button></td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="simpan" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</html>