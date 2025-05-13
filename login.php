<?php
$conn = new mysqli("localhost", "root", "", "cerdas_finansial");
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['saldo'] = $user['saldo'];
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerdas Finansial</title>
    <link rel="stylesheet" href="./assets/style/login.css">
</head>
<body>
    <div class="formLogin">
        <h2>Login</h2>
        <form method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br>
            <label>Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="checkbox" onclick="togglePassword()"> Tampilkan Password<br><br>
            <button type="submit">Login</button>
        </form>
        <p style="color:red;"><?php echo $message; ?></p>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
        <p><a href="index.php">Back</a></p>
    </div>

    <script>
    function togglePassword() {
        const pw = document.getElementById("password");
        pw.type = pw.type === "password" ? "text" : "password";
    }
    </script>
</body>