<?php
include 'backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch saved outfits
$sql = "SELECT * FROM saved_outfits WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
$saved_outfits = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $saved_outfits[] = $row;
    }
}

// Debug: Display the saved outfits array
echo '<pre>';
print_r($saved_outfits);
echo '</pre>';

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Saved Outfits</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #fbeff7; /* Light pink background */
        }
        .container {
            background-color: #ffffff; /* White background for content */
            border-radius: 8px; /* Rounded corners */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .btn-primary {
            background-color: #ff69b4; /* Pink button color */
            border-color: #ff69b4; /* Pink button border */
        }
        .btn-primary:hover {
            background-color: #ff85c0; /* Lighter pink on hover */
            border-color: #ff85c0; /* Lighter pink border on hover */
        }
        .list-group-item {
            border: 1px solid #ff69b4; /* Pink border for list items */
            border-radius: 8px; /* Rounded corners */
        }
        .alert-danger {
            background-color: #f8d7da; /* Light pink background for alerts */
            border-color: #f5c6cb; /* Light pink border for alerts */
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Danica</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Saved Outfits</h2>
        <div class="mt-4">
            <?php if (!empty($saved_outfits)): ?>
                <ul class="list-group">
                    <?php foreach ($saved_outfits as $outfit): ?>
                        <li class="list-group-item">
                            <?php
                            $outfit_details = json_decode($outfit['outfit_details'], true);
                            foreach ($outfit_details as $item): ?>
                                <strong><?php echo htmlspecialchars($item['item_name']); ?></strong> -
                                <?php echo htmlspecialchars($item['type']); ?> -
                                <?php echo htmlspecialchars($item['color']); ?>
                                <br>
                                <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 100px;">
                                <br>
                            <?php endforeach; ?>
                            <small>Saved on: <?php echo htmlspecialchars($outfit['created_at']); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No saved outfits found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
