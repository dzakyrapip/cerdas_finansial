<<<<<<< HEAD
<?php
session_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$nama = $_SESSION['nama'];

$conn = new mysqli("localhost", "root", "", "cerdas_finansial");

$stmt = $conn->prepare("SELECT tipe, jumlah, deskripsi, tanggal FROM transaksi WHERE username = ? ORDER BY tanggal DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>
<h2>Laporan Transaksi - <?php echo htmlspecialchars($nama); ?></h2>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <thead>
        <tr>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo ucfirst($row['tipe']); ?></td>
            <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
            <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
            <td><?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("laporan_transaksi_" . $username . ".pdf", ["Attachment" => true]);
?>
=======
<?php
session_start();

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$nama = $_SESSION['nama'];

$conn = new mysqli("localhost", "root", "", "cerdas_finansial");

$stmt = $conn->prepare("SELECT tipe, jumlah, deskripsi, tanggal FROM transaksi WHERE username = ? ORDER BY tanggal DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

ob_start();
?>
<h2>Laporan Transaksi - <?php echo htmlspecialchars($nama); ?></h2>
<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <thead>
        <tr>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo ucfirst($row['tipe']); ?></td>
            <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
            <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
            <td><?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("laporan_transaksi_" . $username . ".pdf", ["Attachment" => true]);
?>
>>>>>>> 5e6b652a43fd74e9a0df44d59348e619a49b6711
