<?php
include 'backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $outfit_details = json_decode($_POST['outfit_details'], true);

    // Prepare outfit details for database
    foreach ($outfit_details as &$item) {
        $item['image_path'] = $item['image'];  // Use the provided image path
    }

    // Encode the modified array back to JSON
    $outfit_details_json = json_encode($outfit_details);

    // Save the outfit to the database
    $stmt = $conn->prepare("INSERT INTO saved_outfits (user_id, outfit_details) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $outfit_details_json);

    if ($stmt->execute()) {
        echo "Outfit saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    exit();
}
?>
