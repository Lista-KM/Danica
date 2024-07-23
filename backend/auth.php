<?php
include 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if any required field is empty
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../signup.php?error=empty_fields");
        exit();
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email is already registered
        $stmt->close();
        $conn->close();
        header("Location: ../signup.php?error=email_taken");
        exit();
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // Registration successful, redirect to the login page
            $stmt->close();
            $conn->close();
            header("Location: ../login.php?success=registered");
            exit();
        } else {
            // Error inserting the user
            $stmt->close();
            $conn->close();
            header("Location: ../signup.php?error=registration_failed");
            exit();
        }
    }
} else {
    // Redirect to signup page if accessed directly without POST data
    header("Location: ../signup.php");
    exit();
}
?>
