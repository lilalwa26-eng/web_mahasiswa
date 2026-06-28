<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Direct password comparison (for demo)
        if ($password === $user['password']) {
            $_SESSION['username'] = $username;
            header('Location: ../dashboard.php');
            exit();
        } else {
            header('Location: login.php?error=1');
            exit();
        }
    } else {
        header('Location: login.php?error=1');
        exit();
    }
}
?>