<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cerdas_finansial");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$nama = $_SESSION['nama'];

$getUser = $conn->prepare("SELECT saldo FROM users WHERE username = ?");
$getUser->bind_param("s", $username);
$getUser->execute();
$result = $getUser->get_result();
$row = $result->fetch_assoc();
$saldo = $row['saldo'];
$_SESSION['saldo'] = $saldo;

$message = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipe = $_POST['tipe'];
    $jumlah = intval($_POST['jumlah']);
    $deskripsi = trim($_POST['deskripsi']);

    if ($tipe === "keluar" && $jumlah > $saldo) {
        $message = "Transaksi gagal: saldo tidak cukup.";
        $success = false;
    } else {
        $saldo_baru = ($tipe === "masuk") ? $saldo + $jumlah : $saldo - $jumlah;

        $insert = $conn->prepare("INSERT INTO transaksi (username, tipe, jumlah, tanggal, deskripsi) VALUES (?, ?, ?, NOW(), ?)");
        $insert->bind_param("ssis", $username, $tipe, $jumlah, $deskripsi);
        $insert->execute();

        $update = $conn->prepare("UPDATE users SET saldo = ? WHERE username = ? ");
        $update->bind_param("is", $saldo_baru, $username);
        $update->execute();

        $_SESSION['saldo'] = $saldo_baru;
        $message = "Transaksi berhasil!";
        $success = true;
    }
}

// Ambil histori transaksi
$histori = $conn->prepare("SELECT tipe, jumlah, tanggal, deskripsi FROM transaksi WHERE username = ? ORDER BY tanggal DESC LIMIT 2");
$histori->bind_param("s", $username);
$histori->execute();
$hasilHistori = $histori->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerdas Finansial</title>
    <link rel="stylesheet" href="./assets/style/dashboard.css">
</head>
<body>
    <div class="header">
        <h2>Selamat datang di <span style="color: #ffd700;">Cerdas Finansial</span>, <?php echo htmlspecialchars($nama); ?>!</h2>
        <p class="sub">@<?php echo htmlspecialchars($username); ?></p>
        <h3>Saldo Anda: <span class="saldo">Rp <?php echo number_format($_SESSION['saldo'], 0, ',', '.'); ?></span></h3>

        <?php if ($message): ?>
            <div class="notif <?php echo $success ? 'success' : 'error'; ?>" id="notif">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="button-action">
            <a href="download_excel.php" class="excel-button">Excel</a>
            <a href="download_pdf.php" class="pdf-button">PDF</a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="left">
            <form method="POST" class="form-transaksi">
                <div class="form-group">
                    <label>Jenis Transaksi</label>
                    <select name="tipe" required>
                        <option value="masuk">Uang Masuk</option>
                        <option value="keluar">Uang Keluar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Uang</label>
                    <input type="number" name="jumlah" required min="1">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" required></textarea>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="right">
            <h3>Histori Transaksi</h3>
            <div class="histori">
                <?php
                // Query untuk mendapatkan 2 transaksi terbaru
                $hasilHistori = $conn->prepare("SELECT * FROM transaksi WHERE username = ? ORDER BY tanggal DESC LIMIT 2");
                $hasilHistori->bind_param("s", $username);
                $hasilHistori->execute();
                $result = $hasilHistori->get_result();
                
                while($t = $result->fetch_assoc()):
                ?>
                    <div class="item <?php echo $t['tipe']; ?>">
                        <div class="info">
                            <strong><?php echo ucfirst($t['tipe']); ?>:</strong>
                            Rp <?php echo number_format($t['jumlah'], 0, ',', '.'); ?>
                        </div>
                        <div class="desc"><?php echo htmlspecialchars($t['deskripsi']); ?></div>
                        <div class="tanggal"><?php echo date('d M Y, H:i', strtotime($t['tanggal'])); ?></div>
                    </div>
                <?php endwhile; ?>

                <button class="lihat-semua" id="lihatSemuaBtn">Lihat Semua</button>
            </div>
        </div>

        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close-btn" id="closePopupBtn">&times;</span>
                <h3>Semua Transaksi</h3>
                <table class="popup-table">
                    <tr>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                    </tr>
                    <?php
                    $hasilHistoriSemua = $conn->prepare("SELECT * FROM transaksi WHERE username = ? ORDER BY tanggal DESC");
                    $hasilHistoriSemua->bind_param("s", $username);
                    $hasilHistoriSemua->execute();
                    $resultSemua = $hasilHistoriSemua->get_result();

                    while($t = $resultSemua->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo ucfirst($t['tipe']); ?></td>
                            <td>Rp <?php echo number_format($t['jumlah'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($t['deskripsi']); ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($t['tanggal'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    <script>
        <?php if ($message): ?>
            document.addEventListener("DOMContentLoaded", function() {
                const notif = document.getElementById('notif');
                
                notif.style.display = "block";

                setTimeout(function() {
                    notif.style.display = "none";
                }, 3000);
            });
        <?php endif; ?>
        document.getElementById("lihatSemuaBtn").addEventListener("click", function() {
            document.getElementById("popup").style.display = "flex";
        });

        document.getElementById("closePopupBtn").addEventListener("click", function() {
            document.getElementById("popup").style.display = "none";
        });
    </script>
</body>
</html>
