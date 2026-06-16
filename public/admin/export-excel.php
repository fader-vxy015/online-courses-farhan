<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pendaftarkursus.xls");

require_once '../../src/config/koneksi.php';
require_once '../../src/includes/functions.php';

echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>WA</th>
        <th>Jenis Kursus</th>
        <th>Jenjang</th>
      </tr>";

$no = 1;
$data = mysqli_query($conn, "SELECT * FROM pendaftar");
while ($row = mysqli_fetch_assoc($data)) {
    echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>{$row['nama']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['wa']}</td>";
    echo "<td>{$row['jenis_kursus']}</td>";
    echo "<td>{$row['jenjang']}</td>";
    echo "</tr>";
    $no++;
}
echo "</table>";
?>
