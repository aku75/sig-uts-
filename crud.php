<?php
include 'db_connection.php';

   // Tangani penambahan, pembaruan, dan penghapusan data jika formulir dikirim
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add'])) {
      $name = $_POST['name'];
      $address = $_POST['address'];
      $latitude = $_POST['latitude'];
      $longitude = $_POST['longitude'];

      $sql = "INSERT INTO health_services (name, address, latitude, longitude) VALUES ('$name', '$address', $latitude, $longitude)";
      $conn->query($sql);
 } elseif (isset($_POST['update'])) {
      $id = $_POST['id'];
      $name = $_POST['name'];
      $address = $_POST['address'];
      $latitude = $_POST['latitude'];
      $longitude = $_POST['longitude'];

      // Sanitasi data input
      $id = $conn->real_escape_string($id);
      $name = $conn->real_escape_string($name);
      $address = $conn->real_escape_string($address);
      $latitude = $conn->real_escape_string($latitude);
      $longitude = $conn->real_escape_string($longitude);
     
     $sql = "UPDATE health_services SET name='$name',address='$address', latitude=$latitude, longitude=$longitude WHERE id=$id";
     if ($conn->query($sql) === TRUE) { echo "Data berhasil diperbarui";
  } else {
 echo "Error: " . $conn->error;
}
} elseif (isset($_POST['delete'])) {
$id = $_POST['id'];
$sql = "DELETE FROM health_services WHERE id=$id";
$conn->query($sql);
       }
}
$result = $conn->query("SELECT * FROM health_services");
?>


<!DOCTYPE html><html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRUD Layanan Kesehatan</title>
<script>
function confirmDelete() {
return confirm("Anda yakin ingin menghapus data ini?");
}
   </script>
</head>
<body>
<h1>Data Layanan Kesehatan di Kabupaten Banyumas</h1>
<a href="index.php" target="_blank">
<button>Halaman Utama</button>
</a>
<table border="1">
<tr>
             <th>ID</th>
             <th>Nama</th>
             <th>Alamat</th>
             <th>Latitude</th>
             <th>Longitude</th>
             <th>Actions</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['address']; ?></td>
              <td><?php echo $row['latitude']; ?></td>
              <td><?php echo $row['longitude']; ?></td>
              <td>
<!-- Formulir untuk mengedit data -->
<form action="edit.php" method="get"
style="display:inline;">
<input type="hidden" name="id" value="<?php echo
$row['id']; ?>">
<button type="submit">Edit</button>
</form>
<!-- Formulir untuk menghapus data dengan konfirmasi -->
<form action="crud.php" method="post"
style="display:inline;" onsubmit="return confirmDelete();">
<input type="hidden" name="id" value="<?php echo
$row['id']; ?>">
<button type="submit" name="delete">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</table>
<h2>Tambah Data Baru</h2>
            <form action="crud.php" method="post">
            <input type="text" name="name" placeholder="Nama" required>
            <input type="text" name="address" placeholder="Alamat" required>
            <input type="number" step="any" name="latitude"
            placeholder="Latitude" required>
              <input type="number" step="any" name="longitude"
             placeholder="Longitude" required>
            <button type="submit" name="add">Tambah</button>
</form>
</body>
</html>
<?php $conn->close(); ?>