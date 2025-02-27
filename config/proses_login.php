<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('koneksi.php');

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    // $md5_password = md5($password); // Convert to MD5

    // Get user from database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Simple comparison instead of password_verify
        // if ($md5_password == $user['password']) {
        if ($password == $user['password']) {
            // Login logic remains the same
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $_SESSION['toastr'] = [
                'type' => 'success',
                'message' => 'Selamat datang, ' . $user['username'] . '!'
            ];

            header("Location: ../index.php?menu=Dashboard");
            exit;
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: ../login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
        header("Location: ../login.php");
        exit;
    }
}
?>