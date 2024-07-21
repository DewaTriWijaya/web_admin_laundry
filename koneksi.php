<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_laundry";

// ini cuma ubuntu (hapus(null, socket))
$socket = "/opt/lampp/var/mysql/mysql.sock";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname, null, $socket);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
