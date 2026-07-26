<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mumma's Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        /* Footer Styling */
        footer {
            background-color:rgb(233, 75, 130);;
            color: white;
            text-align: center;
            padding: 30px 0;
        }

        footer .footer-columns {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        footer .footer-column {
            flex: 1;
            padding: 15px;
            text-align: left;
            color: white;
        }

        footer .footer-column h3 {
            font-size: 18px;
            color:rgb(29, 26, 27); /* Light pink */
            margin-bottom: 15px;
        }

        footer .footer-column a {
            color: white;
            text-decoration: none;
        }

        footer .footer-column a:hover {
            color:rgb(13, 12, 12);
        }

        /* Social Media Icons Styling */
        footer .social-media a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        footer .social-media a:hover {
            color:rgb(15, 13, 14);
        }

        /* Footer Bottom Section Styling */
        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: white;
        }

        .footer-bottom p {
            margin: 5px 0;
        }

        .footer-bottom strong {
            color: #ff80ab; /* Light pink */
        }
         /* General Body Styling */
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(190, 242, 241); /* Soft blue background */
        }

        /* Header and Navigation Styling */
        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #ff4081; /* Bright pink */
            color: white;
            align-items: center;
        }

        .logo h1 {
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        nav ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #ff80ab; /* Light pink */
            border-radius: 5px;
        }

        /* Search Bar Styling */
        .search-bar {
            margin-right: 20px;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 8px;
            font-size: 14px;
            border: 2px solid #ff80ab;
            border-radius: 5px;
            width: 200px;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #ff80ab;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .search-bar button:hover {
            background-color: #ff4081; /* Darker pink */
        }

        /* Profile Button and Dropdown */
        .profile-button {
            position: relative;
            display: inline-block;
        }

        .profile-button button {
            padding: 10px 20px;
            background-color:rgb(6, 147, 147);; /* Green */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .profile-button button:hover {
            background-color:rgb(2, 67, 69); /* Dark green */
        }

        .profile-button i {
            margin-right: 8px;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #ffffff;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown-menu a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            color: #333;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        /* Show dropdown when the button is hovered */
        .profile-button:hover .dropdown-menu {
            display: block;
        }

        .auth-buttons {
            margin-left: 15px;
        }

        /* Add to Cart and Wishlist Buttons Styling */
        .header-right .add-to-cart,
        .header-right .wishlist {
            background-color:rgb(3, 109, 97); /* Teal */
            color: white;
            padding: 10px 15px;
            margin-left: 10px;
            border-radius: 5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .header-right .add-to-cart:hover,
        .header-right .wishlist:hover {
            background-color:rgb(6, 150, 126); /* Darker teal */
        }

        .header-right .add-to-cart i,
        .header-right .wishlist i {
            margin-right: 5px;
        }
           /* Login Button Styling */
           .login-button {
            background-color:rgb(6, 84, 71);/* Teal */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: rgb(6, 150, 126); /* Darker teal */
        }
        /* Profile Button Styling */
    .profile-button {
        position: relative;
        display: inline-block;
    }

    .profile-button button {
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 10px;
    }

    /* Avatar Circle Styling */
    .profile-avatar {
        width: 40px;
        height: 40px;
        background-color: #06b192; /* Change to your preferred color */
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        font-size: 18px;
        margin-right: 10px;
    }

    /* Profile Name Styling */
    .profile-name {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    /* Dropdown Menu Styling */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background-color: #fff;
        border: 1px solid #ccc;
        min-width: 160px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        z-index: 1;
    }

    .dropdown-menu a {
        padding: 12px 16px;
        text-decoration: none;
        color: #333;
        display: block;
    }

    .dropdown-menu a:hover {
        background-color: #f1f1f1;
    }

    /* Show dropdown when profile button is clicked */
    .profile-button:hover .dropdown-menu {
        display: block;
    }
</style>
        </style>
    </head>
<body>
<!-- Header Section -->
<header>
        <div class="navbar">
            <div class="logo">
                <h1><a href="home.php" style="color: white; text-decoration: none;">Mumma's Care</a></h1>
            </div>
            <div class="header-right">
                <!-- Navigation Menu -->
                <nav>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="category.php">Product</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="privacy policy.php">Privacy Policy</a></li>
                    </ul>
                </nav>

                <!-- Search Bar -->
                <form class="search-bar" action="search_result.php" method="GET">
                    <input type="text" name="search" placeholder="Search Products..." required>
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <!-- Login / Profile Section -->
                
                <div class="auth-buttons">
            <?php if (isset($_SESSION['customer_id'])): ?>
             <div class="profile-button">
            <!-- Profile Button with Circle Avatar -->
            <button onclick="toggleDropdown()">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($_SESSION['customer_name'], 0, 1)); ?>
                </div>
                <!-- Profile Name Text -->
                <span class="profile-text">Profile</span>
            </button>
            <!-- Dropdown Menu with Customer Information -->
            <div class="dropdown-menu">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['customer_email']); ?></p>
                <a href="profile.php">View Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    <?php else: ?>
        <a href="login.php" class="login-button">Login</a>
    <?php endif; ?>
</div>

                <!-- Add to Cart and Wishlist -->
                <a href="add_to_cart.php" class="add-to-cart">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a href="wishlist.php" class="wishlist">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
            </div>
        </div>
    </header>
</body>
</html>