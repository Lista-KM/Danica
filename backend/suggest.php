<?php
include 'db.php';
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
}

// Initialize variables for weather data and occasion
$weather_error = "";
$condition = 'mild'; // Default condition
$temp = "N/A";
$suggested_outfit = [];

// Default latitude and longitude
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '52.52'; 
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '13.41'; 

// Weather API URL
$weather_url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current=temperature_2m,wind_speed_10m";

if ($latitude && $longitude) {
    $weather_data = @file_get_contents($weather_url);

    if ($weather_data === FALSE) {
        $weather_error = "Could not retrieve weather data. Showing default suggestions.";
    } else {
        $weather_data = json_decode($weather_data, true);

        // Log the weather data for debugging
        file_put_contents('weather_debug.log', print_r($weather_data, true));

        // Check if 'current' and 'temperature_2m' exist in the response
        if (!isset($weather_data['current']['temperature_2m'])) {
            $weather_error = "Incomplete weather data. Showing default suggestions.";
        } else {
            $temp = $weather_data['current']['temperature_2m'];

            // Determine weather condition based on temperature
            if ($temp < 10) {
                $condition = 'cold';
            } elseif ($temp >= 10 && $temp < 25) {
                $condition = 'mild';
            } else {
                $condition = 'hot';
            }
        }
    }
}

// Process form submission to get the occasion and suggest outfits
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $occasion = isset($_POST['occasion']) ? $_POST['occasion'] : '';

    // Initialize variables to keep track of selected items
    $chosen_types = [];
    $dress = null;
    $bottom = $top = $cardigan = null;

    foreach ($items as $item) {
        // Debugging: Log each item being processed
        file_put_contents('items_debug.log', print_r($item, true), FILE_APPEND);

        // Check if item fits the weather condition
        if (($condition == 'hot' && $item['category'] == 'summer') ||
            ($condition == 'mild' && $item['category'] == 'spring_fall') ||
            ($condition == 'cold' && $item['category'] == 'winter')) {

            if ($item['occasion'] == $occasion || $occasion == '') { // Match occasion if provided

                // Handle dress separately
                if ($item['category'] == 'dress') {
                    $dress = $item;
                } else {
                    // Ensure only one item per type
                    if (!in_array($item['category'], $chosen_types)) {
                        $chosen_types[] = $item['category'];

                        if ($item['category'] == 'bottom') {
                            $bottom = $item;
                        } elseif ($item['category'] == 'top') {
                            $top = $item;
                        } elseif ($item['category'] == 'cardigan') {
                            $cardigan = $item;
                        }
                    }
                }
            }
        }
    }

    // Debugging: Log chosen items
    file_put_contents('chosen_items_debug.log', print_r([
        'bottom' => $bottom,
        'top' => $top,
        'cardigan' => $cardigan,
        'dress' => $dress
    ], true), FILE_APPEND);

    // Create suggested outfit based on the conditions
    if ($dress) {
        // For cold weather, add cardigan if available
        $suggested_outfit[] = $dress;
        if ($condition == 'cold' && $cardigan) {
            $suggested_outfit[] = $cardigan;
        }
    } else {
        // Combine top, bottom, and cardigan if applicable
        if ($condition == 'cold') {
            if ($bottom && $top) {
                $suggested_outfit[] = $bottom;
                $suggested_outfit[] = $top;
                if ($cardigan) {
                    $suggested_outfit[] = $cardigan;
                }
            } elseif ($top) {
                $suggested_outfit[] = $top;
            } elseif ($bottom) {
                $suggested_outfit[] = $bottom;
            }
        } elseif ($condition == 'mild') {
            if ($bottom && $top) {
                $suggested_outfit[] = $bottom;
                $suggested_outfit[] = $top;
            } elseif ($top) {
                $suggested_outfit[] = $top;
            } elseif ($bottom) {
                $suggested_outfit[] = $bottom;
            }
        } elseif ($condition == 'hot') {
            if ($bottom && $top) {
                $suggested_outfit[] = $bottom;
                $suggested_outfit[] = $top;
            } elseif ($top) {
                $suggested_outfit[] = $top;
            } elseif ($bottom) {
                $suggested_outfit[] = $bottom;
            }
        }
    }
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
</head>
<body>
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
                            <?php echo htmlspecialchars($item['category']); ?> -
                            <?php echo htmlspecialchars($item['color']); ?>
                            <br>
                            <img src="../uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" style="width: 100px;">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No suitable outfits found for the selected occasion and current weather.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Geolocation Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                }, function(error) {
                    console.error('Error getting geolocation:', error);
                    alert("Unable to retrieve your location. Please check your browser's location settings.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    </script>
</body>
</html>
