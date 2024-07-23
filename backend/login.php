<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $username, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $username;
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Invalid password
            header("Location: ../login.php?error=invalid_password");
            exit();
        }
    } else {
        // User does not exist
        header("Location: ../login.php?error=user_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}

