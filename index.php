<?php

if(empty($_SESSION) || !isset($_SESSION['user']) || $_SESSION['user']['username'] == '') {
    header("Location: ./login.php");
    exit();
}

header("Location: ./penjualan.php");
exit();
