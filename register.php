<?php
$conn = new mysqli("localhost", "root", "", "cerdas_finansial");

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "Username sudah digunakan.";
    } elseif ($password !== $conf_password) {
        $message = "Password dan Konfirmasi tidak cocok.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, nama, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $nama, $hashed_password);
        if ($insert->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Gagal registrasi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cerdas Finansial</title>
  <link rel="stylesheet" href="./assets/style/register.css">
</head>
<body>
  <div class="formRegis">
    <h2>Registrasi</h2>
    <form method="post">
      <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br>
        <label>Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label>Konfirmasi Password:</label><br>
        <input type="password" id="conf_password" name="conf_password" required><br>
        <input type="checkbox" onclick="togglePassword()"> Tampilkan Password<br><br>
        <button type="submit">Daftar</button>
    </form>
    <p style="color:red;"><?php echo $message; ?></p>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
    <p><a href="index.php">Back</a></p>
  </div>

  <script>
    function togglePassword() {
      const pw = document.getElementById("password");
      const cpw = document.getElementById("conf_password");
      pw.type = pw.type === "password" ? "text" : "password";
      cpw.type = cpw.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>