# Mumma's Care 🛍️
**Online Baby Product Shopping System**

A full-featured e-commerce web application built with PHP, MySQL, and XAMPP for selling baby products online.

---

## Features

### Customer Side
- Browse products by category
- Search products
- Add to cart / Buy now
- Wishlist functionality
- User registration & login
- Checkout with Razorpay payment gateway
- Order tracking & history
- Contact form
- About, Privacy Policy, Terms & Conditions pages

### Admin Panel
- Secure admin login
- Dashboard with stats & charts (orders, products, customers)
- Manage Products (Add, Update, Delete with image upload)
- Manage Customers
- View Orders & Order Items
- View Contact Messages
- Generate CSV Reports (Customer, Product, Order)

---

## Tech Stack

| Layer      | Technology          |
|------------|---------------------|
| Backend    | PHP 8.x             |
| Database   | MySQL (MariaDB)     |
| Frontend   | HTML, CSS, JavaScript |
| Payment    | Razorpay            |
| Server     | XAMPP (Apache)      |

---

## Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/mummas-care.git
   ```

2. **Move to XAMPP htdocs**
   ```
   Place the project folder inside: C:\xampp\htdocs\
   ```

3. **Import the database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a database named `mumma's_care`
   - Import the SQL file: `fix_db_name.sql`

4. **Start XAMPP**
   - Start Apache and MySQL from XAMPP Control Panel

5. **Open in browser**
   ```
   http://localhost/E010/E010/
   ```

---

## Admin Login

```
URL: http://localhost/E010/E010/admin/admin_login.php
```

---

## Folder Structure

```
E010/
├── admin/          → Admin panel (dashboard, products, orders, etc.)
├── image/          → Product images by category
│   └── uploads/    → Admin-uploaded product images
├── includes/       → Shared files (header, footer, db connect)
├── pages/          → Customer-facing pages
├── razorpay-php/   → Razorpay SDK
└── index.php       → Entry point
```

---

## License
This project is for educational purposes.
