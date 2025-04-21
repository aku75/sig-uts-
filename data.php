<?php
include 'db/koneksi.php';

$sql = "SELECT * FROM faskes";
$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
echo json_encode($data);
?>
