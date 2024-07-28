<?php
include 'backend/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $preferences = $_POST['preferences'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);

        // Check file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $valid_extensions)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }

        // Check file size (limit to 5MB)
        if ($_FILES['profile_picture']['size'] > 5000000) {
            echo "Sorry, your file is too large.";
            exit();
        }

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $sql = "UPDATE profiles SET first_name=?, last_name=?, email=?, profile_picture=?, preferences=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
            $stmt->bind_param("sssssi", $first_name, $last_name, $email, $target_file, $preferences, $user_id);

            if ($stmt->execute()) {
                echo "Profile updated successfully with picture.";
            } else {
                echo "Error updating profile with picture: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // Update without changing the profile picture
        $sql = "UPDATE profiles SET first_name=?, last_name=?, email=?, preferences=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        }
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $preferences, $user_id);

        if ($stmt->execute()) {
            echo "Profile updated successfully without picture.";
        } else {
            echo "Error updating profile without picture: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Check if profile exists
$sql = "SELECT * FROM profiles WHERE id=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
} else {
    $profile = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'profile_picture' => '',
        'preferences' => ''
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Your Profile</h2>
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($profile['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($profile['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($profile['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control-file">
                <?php if ($profile['profile_picture']): ?>
                    <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture" style="width: 100px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="preferences">Preferences</label>
                <textarea name="preferences" id="preferences" class="form-control"><?php echo htmlspecialchars($profile['preferences']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
