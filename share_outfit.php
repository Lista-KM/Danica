<?php
session_start();
include 'backend/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Retrieve outfit_id from the URL, or set a default value if not present
$outfit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the outfit_id is valid
if ($outfit_id <= 0) {
    echo "Invalid outfit ID.";
    exit();
}

// Fetch the outfit details
$sql = "SELECT * FROM wardrobe WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $outfit_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Outfit not found.";
    exit();
}

$outfit = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Handle sharing logic
$share_method = isset($_POST['share_method']) ? htmlspecialchars($_POST['share_method']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $share_method) {
    $item_name = urlencode($outfit['item_name']);
    $item_image = urlencode("uploads/" . $outfit['image_path']);
    $item_url = urlencode("http://yourwebsite.com/outfit.php?id=$outfit_id"); // Update this URL to the correct page
    $message = "Check out this outfit: $item_name";

    switch ($share_method) {
        case 'facebook':
            $share_url = "https://www.facebook.com/sharer/sharer.php?u=$item_url&quote=$message";
            break;
        case 'twitter':
            $share_url = "https://twitter.com/intent/tweet?text=$message&url=$item_url";
            break;
        case 'whatsapp':
            $share_url = "https://api.whatsapp.com/send?text=$message $item_url";
            break;
        
        default:
            echo "Invalid share method.";
            exit();
    }

    header("Location: $share_url");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Share Outfit</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Share Your Outfit</h2>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo htmlspecialchars($outfit['item_name']); ?></h4>
                <p class="card-text">
                    Category: <?php echo htmlspecialchars($outfit['category']); ?><br>
                    Color: <?php echo htmlspecialchars($outfit['color']); ?><br>
                    Rating: <?php echo htmlspecialchars($outfit['rating']); ?><br>
                    Occasion: <?php echo htmlspecialchars($outfit['occasion']); ?><br>
                    Type: <?php echo htmlspecialchars($outfit['type']); ?><br>
                </p>
                <img src="../uploads/<?php echo htmlspecialchars($outfit['image_path']); ?>" class="img-fluid" alt="Outfit Image">
                
                <form method="post">
                    <div class="form-group">
                        <label for="share_method">Share Method:</label>
                        <select name="share_method" id="share_method" class="form-control" required>
                            <option value="" disabled selected>Select share method</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="instagram">Instagram</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Share</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
