<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Sign Up</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
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
        .form-control {
            border-radius: 5px; 
        }
        h2 {
            color: #ff69b4; 
        }
        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Sign Up</h2>
        <form id="signupForm" action="backend/auth.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                <div id="confirmPasswordError" class="error-message"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
        </form>
    </div>

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom script for form validation -->
    <script>
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var errorElement = document.getElementById('confirmPasswordError');

            if (password !== confirmPassword) {
                errorElement.textContent = 'Passwords do not match';
         
