<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mumma's Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        </style>
  </head>
  <body>      
 <!-- Footer Section -->
 <footer>
        <div class="footer-content">
            <div class="footer-columns">
                <!-- Get in Touch Section -->
                <div class="footer-column">
                    <h3>Get in Touch</h3>
                    <p>Email: support@mumma's care.com</p>
                    <p>Phone: +123-456-7890</p>
                </div>

                <!-- Follow Us Section -->
                <div class="footer-column">
                    <h3>Follow Us</h3>
                    <div class="social-media">
                        <a href="https://www.facebook.com" target="_blank" title="Follow us on Facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a> 
                        <a href="https://www.twitter.com" target="_blank" title="Follow us on Twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.instagram.com" target="_blank" title="Follow us on Instagram">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                    </div>
                </div>

                <!-- Company Info Section -->
                <div class="footer-column">
                    <h3>Company Info</h3>
                    <p><a href="about.php">About Us</a></p>
                    <p><a href="privacy policy.php">Privacy Policy</a></p>
                    <p><a href="terms-conditions.php">Terms & Conditions</a></p>
                </div>

                <!-- Purchase Info Section -->
                <div class="footer-column">
                    <h3>Purchase Info</h3>
                    <p><a href="contact.php">Contact Us</a></p>
                    <p><a href="return_policy.php">Return Policy</a></p>
                    <p><a href="my_order.php">My order</a></p>
                </div>
            </div>

            <p>&copy; 2024 Mumma's Care. All rights reserved.</p>
        </div>
    </footer>
    </body>
  </html>  