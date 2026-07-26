<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['search'])) {
    $name        = $_POST['name'] ?? '';
    $price       = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $stock       = $_POST['stock'] ?? '';
    $category    = $_POST['category'] ?? '';
    $image       = basename($_FILES['image']['name'] ?? '');
    $image_temp  = $_FILES['image']['tmp_name'] ?? '';

    // Save to image/uploads/ so user side shows it via ../image/uploads/
    $upload_dir  = '../image/uploads/';
    $image_db    = 'uploads/' . $image;   // DB stores: uploads/filename.jpg
    $save_path   = $upload_dir . $image;

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    if ($image) move_uploaded_file($image_temp, $save_path);

    if ($name && $price && $category && $description && $stock && $image) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, category, description, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $price, $category, $description, $stock, $image_db);
        if ($stmt->execute()) {
            $message = '<div class="alert success"><i class="fas fa-check-circle"></i> Product added successfully!</div>';
        } else {
            $message = '<div class="alert error"><i class="fas fa-times-circle"></i> Error: ' . $conn->error . '</div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="alert error"><i class="fas fa-times-circle"></i> All fields are required.</div>';
    }
}

$search_query = $_POST['search'] ?? '';
$search_safe  = $conn->real_escape_string($search_query);
$products_result = $conn->query("SELECT * FROM products WHERE name LIKE '%$search_safe%'");

$admin_name = "Mumma's Care";
$admin_profile_image = "OIP (1).jpg";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products – Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f0f2f5; display:flex; min-height:100vh; color:#333; }

        /* SIDEBAR */
        .sidebar { width:260px; background:linear-gradient(180deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%); color:#fff; display:flex; flex-direction:column; position:fixed; top:0; left:0; height:100vh; z-index:100; box-shadow:4px 0 20px rgba(0,0,0,0.3); }
        .sidebar-header { padding:28px 20px 20px; border-bottom:1px solid rgba(255,255,255,0.08); text-align:center; }
        .sidebar-logo { width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #e91e8c; margin-bottom:12px; }
        .sidebar-header h3 { font-size:15px; font-weight:600; color:#fff; }
        .sidebar-header span { font-size:12px; color:#e91e8c; font-weight:500; }
        .sidebar nav { flex:1; padding:20px 12px; overflow-y:auto; }
        .sidebar nav a { display:flex; align-items:center; gap:12px; padding:11px 14px; color:rgba(255,255,255,0.75); text-decoration:none; border-radius:10px; margin-bottom:3px; font-size:14px; font-weight:500; transition:all 0.2s; }
        .sidebar nav a i { width:20px; text-align:center; font-size:15px; }
        .sidebar nav a:hover { background:rgba(233,30,140,0.2); color:#fff; }
        .sidebar nav a.active { background:linear-gradient(135deg,#e91e8c,#c2185b); color:#fff; box-shadow:0 4px 12px rgba(233,30,140,0.4); }
        .sidebar-footer { padding:16px 12px; border-top:1px solid rgba(255,255,255,0.08); }
        .sidebar-footer a { display:flex; align-items:center; gap:10px; padding:11px 14px; color:rgba(255,255,255,0.6); text-decoration:none; border-radius:10px; font-size:14px; transition:all 0.2s; }
        .sidebar-footer a:hover { background:rgba(231,76,60,0.2); color:#e74c3c; }

        /* MAIN */
        .main-content { margin-left:260px; flex:1; display:flex; flex-direction:column; }
        .topbar { background:#fff; padding:0 32px; height:68px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 8px rgba(0,0,0,0.06); position:sticky; top:0; z-index:90; }
        .topbar h1 { font-size:22px; font-weight:700; color:#1a1a2e; }
        .topbar p { font-size:13px; color:#888; margin-top:2px; }
        .topbar-right { display:flex; align-items:center; gap:16px; }
        .topbar-date { font-size:13px; color:#888; background:#f0f2f5; padding:7px 14px; border-radius:20px; }
        .topbar-avatar { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #e91e8c; }
        .page-body { padding:28px 32px; }

        /* ALERTS */
        .alert { padding:12px 18px; border-radius:10px; margin-bottom:20px; font-size:14px; font-weight:500; display:flex; align-items:center; gap:10px; }
        .alert.success { background:#d1fae5; color:#065f46; }
        .alert.error   { background:#fee2e2; color:#991b1b; }

        /* PANEL */
        .panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.06); margin-bottom:28px; overflow:hidden; }
        .panel-header { padding:18px 24px; border-bottom:1px solid #f0f2f5; display:flex; align-items:center; justify-content:space-between; }
        .panel-header h3 { font-size:15px; font-weight:600; color:#1a1a2e; }

        /* SEARCH */
        .search-form { padding:20px 24px; display:flex; gap:12px; }
        .search-form input { flex:1; padding:10px 16px; border:1px solid #e0e0e0; border-radius:10px; font-size:14px; font-family:'Inter',sans-serif; outline:none; transition:border 0.2s; }
        .search-form input:focus { border-color:#e91e8c; }
        .btn { padding:10px 22px; border:none; border-radius:10px; cursor:pointer; font-size:14px; font-weight:600; font-family:'Inter',sans-serif; transition:all 0.2s; }
        .btn-pink { background:linear-gradient(135deg,#e91e8c,#c2185b); color:#fff; }
        .btn-pink:hover { opacity:0.9; }
        .btn-green { background:linear-gradient(135deg,#10b981,#059669); color:#fff; }
        .btn-green:hover { opacity:0.9; }

        /* TABLE */
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { background:#f8fafc; padding:12px 20px; font-size:12px; font-weight:600; color:#888; text-transform:uppercase; letter-spacing:0.5px; text-align:left; }
        .data-table td { padding:13px 20px; font-size:14px; border-bottom:1px solid #f0f2f5; color:#444; }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:#fafafa; }
        .data-table img { width:50px; height:50px; object-fit:cover; border-radius:8px; }
        .btn-update { background:#d1fae5; color:#065f46; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:600; }
        .btn-delete { background:#fee2e2; color:#991b1b; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:600; margin-left:6px; }
        .btn-update:hover { background:#a7f3d0; }
        .btn-delete:hover  { background:#fecaca; }

        /* ADD FORM */
        .add-form { padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:13px; font-weight:600; color:#555; }
        .form-group input,
        .form-group textarea,
        .form-group select { padding:10px 14px; border:1px solid #e0e0e0; border-radius:10px; font-size:14px; font-family:'Inter',sans-serif; outline:none; transition:border 0.2s; }
        .form-group input:focus,
        .form-group textarea:focus { border-color:#e91e8c; }
        .form-group textarea { resize:vertical; min-height:90px; }
        .form-actions { grid-column:1/-1; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="sidebar-logo">
        <h3><?= htmlspecialchars($admin_name) ?></h3>
        <span>Administrator</span>
    </div>
    <nav>
        <a href="dashboard.php"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="report_generation.php"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
        <a href="customer.php"><i class="fas fa-users"></i><span>Customers</span></a>
        <a href="product.php" class="active"><i class="fas fa-box-open"></i><span>Products</span></a>
        <a href="orders.php"><i class="fas fa-shopping-bag"></i><span>Orders</span></a>
        <a href="order_items.php"><i class="fas fa-list-ul"></i><span>Order Items</span></a>
        <a href="contacts.php"><i class="fas fa-envelope"></i><span>Contacts</span></a>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div>
            <h1>Products</h1>
            <p>Manage your product listings</p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date"><i class="fas fa-calendar-alt" style="margin-right:6px;color:#e91e8c;"></i><?= date('D, d M Y') ?></span>
            <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="topbar-avatar">
        </div>
    </div>

    <div class="page-body">
        <?= $message ?>

        <!-- Product List -->
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-box-open" style="color:#e91e8c;margin-right:8px;"></i>All Products</h3>
            </div>
            <form method="POST" class="search-form">
                <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search by product name…">
                <button type="submit" class="btn btn-pink"><i class="fas fa-search"></i> Search</button>
            </form>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Price</th><th>Category</th>
                        <th>Image</th><th>Description</th><th>Stock</th><th>Created At</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products_result && $products_result->num_rows > 0): ?>
                        <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
                            <td><?= htmlspecialchars(substr($product['description'], 0, 60)) ?>…</td>
                            <td><?= $product['stock'] ?? 'N/A' ?></td>
                            <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                            <td>
                                <a href="update_product.php?id=<?= $product['id'] ?>" class="btn-update">Edit</a>
                                <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Delete this product?')" class="btn-delete">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9" style="text-align:center;padding:30px;color:#aaa;">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Product -->
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-plus-circle" style="color:#e91e8c;margin-right:8px;"></i>Add New Product</h3>
            </div>
            <form method="POST" enctype="multipart/form-data" class="add-form">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required placeholder="Enter product name">
                </div>
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="text" name="price" required placeholder="e.g. 499">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" required placeholder="e.g. Baby Gear">
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" required placeholder="Available quantity">
                </div>
                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" required placeholder="Product description…"></textarea>
                </div>
                <div class="form-group full">
                    <label>Product Image</label>
                    <input type="file" name="image" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-green"><i class="fas fa-plus"></i> Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
