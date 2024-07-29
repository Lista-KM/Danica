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
$sql = "SELECT * FROM saved_outfits WHERE user_id='$user_id'";
$result = $conn->query($sql);
$saved_outfits = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $saved_outfits[] = $row;
    }
} else {
    echo "No saved outfits found.";
    exit();
}

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
        .btn-secondary {
            background-color: #f5a3b5; /* Lighter pink button color */
            border-color: #f5a3b5; /* Lighter pink button border */
        }
        .btn-secondary:hover {
            background-color: #f7b8c1; /* Even lighter pink on hover */
            border-color: #f7b8c1; /* Even lighter pink border on hover */
        }
        .list-group-item {
            border: 1px solid #ff69b4; /* Pink border for list items */
            border-radius: 8px; /* Rounded corners */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Your Saved Outfits</h2>
        <ul class="list-group">
            <?php foreach ($saved_outfits as $outfit): ?>
                <?php $outfit_details = json_decode($outfit['outfit_details'], true); ?>
                <li class="list-group-item">
                    <?php foreach ($outfit_details as $item): ?>
                        <strong><?php echo htmlspecialchars($item['item_name']); ?></strong> -
                        <?php echo htmlspecialchars($item['type']); ?> -
                        <?php echo htmlspecialchars($item['color']); ?>
                        <br>
                        <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 100px; border-radius: 8px;">
                    <?php endforeach; ?>
                    <form method="post" action="select_saved_outfit.php">
                        <input type="hidden" name="outfit_id" value="<?php echo $outfit['id']; ?>">
                        <button type="submit" class="btn btn-primary mt-3">Select This Outfit</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>