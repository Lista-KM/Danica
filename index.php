<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }
        .navbar {
            background-color: #ffc0cb; /* Light Pink */
        }
        .navbar-brand, .nav-link {
            color: #000000 !important; /* Black */
        }
        .hero-section {
            background-image: url();
            background-size: cover;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 100px 0;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            color: white; /* Hot Pink */
        }
        .hero-section p {
            font-size: 1.5rem;
            margin: 20px 0;
            color: #add8e6; /* Light Blue */
        }
        .btn-primary {
            background-color: #ff69b4; /* Hot Pink */
            border: none;
        }
        .btn-secondary {
            background-color: #add8e6; /* Light Blue */
            border: none;
        }
        .features i {
            color: #ff69b4; /* Hot Pink */
        }
        .features h3 {
            color: #000000; /* Black */
        }
        .features p {
            color: #6c757d; /* Gray */
        }
        .testimonial blockquote {
            color: #000000; /* Black */
        }
        .testimonial footer {
            color: #ff69b4; /* Hot Pink */
        }
        .cta-section {
            background-color: #add8e6; /* Light Blue */
            color: black;
            padding: 50px 0;
        }
        .footer {
            background-color: #ffc0cb; /* Light Pink */
            color: #000000; /* Black */
            padding: 20px 0;
        }
        .footer a {
            color: #ff69b4; /* Hot Pink */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Danica</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Sign Up</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="hero-section text-center py-5">
        <h1>Discover Your Style</h1>
        <p>Organize your wardrobe, get outfit suggestions, and look your best every day.</p>
        <a href="signup.php" class="btn btn-primary btn-lg mt-3">Get Started</a>
        <a href="login.php" class="btn btn-secondary btn-lg mt-3">Login</a>
    </div>

    <div class="container mt-5">
        <h2 class="text-center" style="color: #ff69b4;">Features</h2>
        <div class="row mt-4 features">
            <div class="col-md-4 text-center">
                <i class="fas fa-tshirt fa-3x"></i>
                <h3>Organize</h3>
                <p>Keep your wardrobe organized and easy to manage.</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-calendar-alt fa-3x"></i>
                <h3>Plan Outfits</h3>
                <p>Get daily outfit suggestions based on weather and occasion.</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="fas fa-user-friends fa-3x"></i>
                <h3>Share</h3>
                <p>Share your favorite outfits with friends and get feedback.</p>
            </div>
        </div>
    </div>

    <div class="container mt-5 testimonial">
        <h2 class="text-center" style="color: #ff69b4;">What Our Users Say</h2>
        <div class="row mt-4">
            <div class="col-md-4 text-center">
                <blockquote class="blockquote">
                    <p>"Danica has transformed the way I plan my outfits. It's a game-changer!"</p>
                    <footer class="blockquote-footer">Jane Doe</footer>
                </blockquote>
            </div>
            <div class="col-md-4 text-center">
                <blockquote class="blockquote">
                    <p>"I love how easy it is to keep track of my clothes and get suggestions."</p>
                    <footer class="blockquote-footer">John Smith</footer>
                </blockquote>
            </div>
            <div class="col-md-4 text-center">
                <blockquote class="blockquote">
                    <p>"The outfit suggestions based on weather are incredibly accurate!"</p>
                    <footer class="blockquote-footer">Emily Johnson</footer>
                </blockquote>
            </div>
        </div>
    </div>

    <div class="cta-section text-center">
        <h2>Ready to Transform Your Wardrobe?</h2>
        <p class="lead">Sign up now and start organizing your wardrobe today!</p>
        <a href="signup.php" class="btn btn-primary btn-lg mt-3">Sign Up</a>
    </div>

    <footer class="footer mt-5 py-3">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>Learn more about Danica and our mission to help you look your best every day.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <a href="#" class="mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="mr-2"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <p class="mt-3">&copy; 2024 Danica. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
