<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Add New Wardrobe Item</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .btn-theme {
            background-color: #ff69b4; 
            color: #fff;
            border: none;
        }
        .btn-theme:hover {
            background-color: #ff1493; 
        }
        .form-label {
            color: #ff69b4; 
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff; 
        }
    </style>
</head>
<body class="bg-gray-100">
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

    <div class="form-container mt-5">
        <h2 class="text-center mb-4 text-pink-500">Add New Wardrobe Item</h2>
        <form action="backend/wardrobe.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item_name" class="form-label">Item Name:</label>
                <input type="text" class="form-control" id="item_name" name="item_name" required>
            </div>
            <div class="form-group">
                <label for="category" class="form-label">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="" disabled selected>Select category</option>
                    <option value="summer">Summer</option>
                    <option value="spring_fall">Spring/Fall</option>
                    <option value="winter">Winter</option>
                </select>
            </div>
            <div class="form-group">
                <label for="color" class="form-label">Color:</label>
                <input type="text" class="form-control" id="color" name="color">
            </div>
            <div class="form-group">
                <label for="rating" class="form-label">Rating:</label>
                <input type="number" class="form-control" id="rating" name="rating" min="0" max="10">
            </div>
            <div class="form-group">
                <label for="occasion" class="form-label">Occasion:</label>
                <select name="occasion" id="occasion" class="form-control">
                    <option value="">Select an occasion</option>
                    <option value="casual">Casual</option>
                    <option value="formal">Formal</option>
                    <option value="sports">Sports</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type" class="form-label">Type:</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Select type</option>
                    <option value="tops">Tops</option>
                    <option value="bottoms">Bottoms</option>
                    <option value="cardigans">Cardigans</option>
                    <option value="accessories">Accessories</option>
                    <option value="dresses">Dresses</option>
                    <option value="others">Others</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-theme btn-block">Add Item</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
