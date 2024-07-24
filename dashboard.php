<?php
session_start();
include 'backend/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wardrobe items including image path
$sql = "SELECT * FROM wardrobe WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wardrobe_items = [];
while ($row = $result->fetch_assoc()) {
    // Sanitize image_path by removing any leading '../uploads/' if present
    $row['image_path'] = preg_replace('/^(\.\.\/uploads\/)+/', '', $row['image_path']);
    $wardrobe_items[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            color: #ff69b4;
            text-shadow: 2px 2px #000;
        }
        .welcome-text {
            font-size: 1.8rem;
            color: blue;
        }
        .wardrobe-image {
            max-width: 200px; /* Adjust as needed */
            height: auto;
        }
        .debug-output {
            color: red;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Danica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center welcome-text">Welcome to Your Wardrobe, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <a href="add_item.php" class="btn btn-primary">Add New Item</a>
        <a href="backend/suggest.php" class="btn btn-secondary">Suggest Outfit</a>
        <div class="mt-4">
            <h3>Your Wardrobe</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Color</th>
                        <th>Rating</th>
                        <th>Last Worn</th>
                        <th>Occasion</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="wardrobe-items">
                    <?php foreach ($wardrobe_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo htmlspecialchars($item['color']); ?></td>
                            <td><?php echo htmlspecialchars($item['rating']); ?></td>
                            <td><?php echo htmlspecialchars($item['last_worn']); ?></td>
                            <td><?php echo htmlspecialchars($item['occasion']); ?></td>
                            <td><?php echo htmlspecialchars($item['type']); ?></td>
                            <td>
                                <?php
                                // Correct path construction
                                $image_path = "uploads/" . htmlspecialchars($item['image_path']);
                                if (file_exists($image_path)) {
                                    echo "<img src='$image_path' alt='Item Image' class='wardrobe-image'>";
                                } else {
                                    echo "<div class='debug-output'>Image not found at $image_path</div>";
                                    echo "<div class='debug-output'>Image path from database: " . htmlspecialchars($item['image_path']) . "</div>";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="edit_item.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_item.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer">
        &copy; 2024 Danica
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>