<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['selected_outfit'])) {
    echo "No outfit selected.";
    exit();
}

$selected_outfit = $_SESSION['selected_outfit'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Selected Outfit</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #fbeff7; 
        }
        .container {
            background-color: #ffffff; 
            border-radius: 8px; 
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        .btn-primary {
            background-color: #ff69b4; 
            border-color: #ff69b4; 
        }
        .btn-primary:hover {
            background-color: #ff85c0; 
            border-color: #ff85c0; 
        }
        .btn-secondary {
            background-color: #f5a3b5; 
            border-color: #f5a3b5; 
        }
        .btn-secondary:hover {
            background-color: #f7b8c1; 
            border-color: #f7b8c1; 
        }
        .list-group-item {
            border: 1px solid #ff69b4; 
            border-radius: 8px; 
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Your Selected Outfit</h2>
        <ul class="list-group">
            <?php foreach ($selected_outfit as $item): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($item['item_name']); ?></strong> -
                    <?php echo htmlspecialchars($item['type']); ?> -
                    <?php echo htmlspecialchars($item['color']); ?>
                    <br>
                    <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 100px; border-radius: 8px;">
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
