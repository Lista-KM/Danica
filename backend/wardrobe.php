<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $rating = $_POST['rating'];
    $occasion = $_POST['occasion'];
    $type = $_POST['type'];

    // Handle file upload for image
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "../uploads/" . basename($image_name); // Adjusted path

    if (!file_exists("../uploads/")) {
        mkdir("../uploads/");
    }

    if (move_uploaded_file($image_tmp, $image_path)) {
        // Insert new item into wardrobe table
        $stmt = $conn->prepare("INSERT INTO wardrobe (user_id, item_name, category, color, rating, occasion, type, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $item_name, $category, $color, $rating, $occasion, $type, $image_path);

        if ($stmt->execute()) {
            // Redirect to dashboard.php after successful insertion
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Handle database insertion error
            header("Location: ../add_item.php?error=database_error");
            exit();
        }
    } else {
        // Handle file upload error
        header("Location: ../add_item.php?error=file_upload_failed");
        exit();
    }
}
?>
