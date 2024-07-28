<?php
session_start();
include 'backend/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $outfit_id = $_POST['outfit_id'];
    
    // Fetch the selected outfit details
    $stmt = $conn->prepare("SELECT * FROM saved_outfits WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $outfit_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $outfit = $result->fetch_assoc();
        // Do something with the selected outfit, e.g., set it as the current outfit, display it, etc.
        $_SESSION['selected_outfit'] = $outfit['outfit_details'];
        header("Location: display_selected_outfit.php");
    } else {
        echo "Outfit not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
    exit();
}
?>
