<?php
include 'backend/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wardrobe items
$sql = "SELECT * FROM wardrobe WHERE user_id='$user_id'";
$result = $conn->query($sql);
$items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
} else {
    echo "No items found.";
    exit();
}

// Fetch weather data
$apiKey = "b43c3d5750ebfb55f2896e3c8ad79c8a"; // Replace with your actual API key
$city = "nairobi"; // Your city
$apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

$response = @file_get_contents($apiUrl);
$weatherData = $response ? json_decode($response, true) : null;

if ($weatherData && isset($weatherData['main']['temp'])) {
    $temp = $weatherData['main']['temp'];
    
    if ($temp < 10) {
        $condition = 'winter';
    } elseif ($temp >= 10 && $temp < 20) {
        $condition = 'spring_fall'; // Adjusted to include 'spring_fall'
    } else {
        $condition = 'summer';
    }
    $weather_error = null;
} else {
    $weather_error = "Weather data not available.";
    $condition = null;
}

// Get selected occasion from POST request
$selected_occasion = isset($_POST['occasion']) ? $_POST['occasion'] : '';

// Initialize arrays for different occasions
$occasion_items = [
    'casual' => [],
    'formal' => [],
    'sports' => []
];

// Group items by occasion
foreach ($items as $item) {
    if ($item['category'] == $condition) {
        if (isset($occasion_items[$item['occasion']])) {
            $occasion_items[$item['occasion']][] = $item;
        }
    }
}

// Suggested outfit logic
$suggested_outfit = [];
$selected_types = [];

// Filter and select outfits based on occasion
if ($selected_occasion && isset($occasion_items[$selected_occasion])) {
    foreach ($occasion_items[$selected_occasion] as $item) {
        if ($item['type'] == 'dresses') {
            // If it's a dress, add it and stop further selection
            $suggested_outfit[] = $item;
            break;
        } elseif (!in_array($item['type'], $selected_types)) {
            $suggested_outfit[] = $item;
            $selected_types[] = $item['type'];
        }
    }
}

// Save Outfit Logic
if (isset($_POST['save_outfit'])) {
    $outfit_details = json_encode($suggested_outfit); // Convert outfit array to JSON
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO saved_outfits (user_id, outfit_details) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $outfit_details);

    if ($stmt->execute()) {
        echo "Outfit saved successfully!";
    } else {
        echo "Error saving outfit: " . $conn->error;
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
    <title>Danica - Suggested Outfit</title>
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
        <?php if ($weather_error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($weather_error); ?>
            </div>
        <?php else: ?>
            <p>Current weather: <?php echo htmlspecialchars($condition); ?>, <?php echo htmlspecialchars($temp); ?>°C</p>
        <?php endif; ?>

        <!-- Occasion selection form -->
        <form action="suggest.php" method="post" class="mt-4">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <label for="occasion">Occasion:</label>
            <select name="occasion" id="occasion" class="form-control">
                <option value="">Select an occasion</option>
                <option value="casual">Casual</option>
                <option value="formal">Formal</option>
                <option value="sports">Sports</option>
            </select>
            <button type="submit" class="btn btn-primary mt-3">Get Outfit Suggestion</button>
        </form>

        <!-- Suggested outfit display -->
        <div class="mt-4">
            <?php if (!empty($suggested_outfit)): ?>
                <h2>Your Suggested Outfit</h2>
                <ul class="list-group">
                    <?php foreach ($suggested_outfit as $item): ?>
                        <li class="list-group-item">
                            <strong><?php echo htmlspecialchars($item['item_name']); ?></strong> -
                            <?php echo htmlspecialchars($item['type']); ?> -
                            <?php echo htmlspecialchars($item['color']); ?>
                            <br>
                            <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 100px;">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No suitable outfit found for the selected occasion.</p>
            <?php endif; ?>
        </div>

        <!-- Save outfit form -->
        <form action="suggest.php" method="post" class="mt-4">
            <input type="hidden" name="save_outfit" value="1">
            <button type="submit" class="btn btn-secondary">Save Outfit</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Fetch and send location -->
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            });
        }
    </script>
</body>
</html>
