<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once '../config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$id) {
    header('Location: index.php');
    exit();
}

$query = "DELETE FROM mahasiswa WHERE id = $id";

if ($conn->query($query) === TRUE) {
    header('Location: index.php?success=delete');
    exit();
} else {
    header('Location: index.php?error=delete');
    exit();
}
?>