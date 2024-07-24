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

// Fetch item details
$sql = "SELECT * FROM wardrobe WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    header("Location: dashboard.php");
    exit();
}

$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $rating = $_POST['rating'];
    $last_worn = $_POST['last_worn'];
    $occasion = $_POST['occasion'];
    $type = $_POST['type'];

    $sql = "UPDATE wardrobe SET item_name = ?, category = ?, color = ?, rating = ?, last_worn = ?, occasion = ?, type = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssii", $item_name, $category, $color, $rating, $last_worn, $occasion, $type, $item_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Item</h2>
        <form method="post">
            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" id="category" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required>
            </div>
            <div class="form-group">
                <label for="color">Color</label>
                <input type="text" class="form-control" id="color" name="color" value="<?php echo htmlspecialchars($item['color']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" value="<?php echo htmlspecialchars($item['rating']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_worn">Last Worn</label>
                <input type="date" class="form-control" id="last_worn" name="last_worn" value="<?php echo htmlspecialchars($item['last_worn']); ?>">
            </div>
            <div class="form-group">
                <label for="occasion">Occasion</label>
                <input type="text" class="form-control" id="occasion" name="occasion" value="<?php echo htmlspecialchars($item['occasion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($item['type']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Item</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
