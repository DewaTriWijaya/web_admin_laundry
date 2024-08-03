<?php
$token = "Vj8Yz71KjTu@PLMm@39F";

// Menghubungkan ke database
include "koneksi.php";

// Memeriksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$nota = $_POST['nota'];
$status = $_POST['status'];
$tanggal = $_POST['tanggal'];

// Query untuk mengambil nomor HP dari tabel nota berdasarkan No_Nota
$sql = "SELECT No_HP FROM nota WHERE No_Nota='$nota'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $target = $row['No_HP'];
    $message = "No Nota: $nota\nStatus Laundry: $status\nTanggal Selesai: $tanggal";

    // Inisiasi CURL untuk mengirim pesan
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.fonnte.com/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'target' => $target,
        'message' => $message,
      ),
      CURLOPT_HTTPHEADER => array(
        "Authorization: $token"
      ),
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);

    if (isset($error_msg)) {
      echo "Error: $error_msg";
    } else {
      echo "Message sent: $response";
    }
} else {
    echo "No phone number found for No_Nota: $nota";
}

mysqli_close($conn);
?>