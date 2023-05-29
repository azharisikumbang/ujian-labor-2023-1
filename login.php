<?php

session_start();

if(isset($_SESSION['user'])) {
    header("Location: ./penjualan.php");
    exit();
}

$database = require_once './database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($database, "SELECT * FROM pengguna WHERE username = '$username'");
    if(mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];

            header("Location: ./penjualan.php");
            exit();
        }
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penjualan Air</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post" class="card-container">
        <div class="card">
            <h3 class="title">Login</h3>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </div>
    </form>
</body>
</html>