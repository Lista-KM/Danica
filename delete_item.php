<?php
session_start();
include 'backend/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the item ID is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$item_id = $_GET['id'];

// Delete the item
$sql = "DELETE FROM wardrobe WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
