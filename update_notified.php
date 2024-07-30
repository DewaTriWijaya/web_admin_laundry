<?php
include "koneksi.php";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nota = $_POST['nota'];

    $sql = "UPDATE Status_Laundry SET notified=1 WHERE No_Nota='$nota' AND status_laundry='Selesai'";

    if ($conn->query($sql) === TRUE) {
        echo "Status notified diperbarui.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
