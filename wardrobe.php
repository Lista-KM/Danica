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

    // Handle file upload for image
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_path = "../uploads/" . basename($image_name);

    if (!file_exists("../uploads/")) {
        mkdir("../uploads/", 0777, true); // Ensure proper permissions and recursive creation
    }

    if (move_uploaded_file($image_tmp, $image_path)) {
        $stmt = $conn->prepare("INSERT INTO wardrobe (user_id, item_name, category, color, rating, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $user_id, $item_name, $category, $color, $rating, $image_path);

        if ($stmt->execute()) {
            header("Location: ../dashboard.php");
            exit();
        } else {
            error_log("SQL Error: " . $stmt->error); // Log error for debugging
            header("Location: ../add_item.php?error=database_error");
            exit();
        }
    } else {
        header("Location: ../add_item.php?error=file_upload_failed");
        exit();
    }
}
?>
