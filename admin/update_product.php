<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$product_result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $product_result ? $product_result->fetch_assoc() : null;

if (!$product) {
    header('Location: product.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $conn->real_escape_string($_POST['name']);
    $price       = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    $stock       = (int)$_POST['stock'];
    $category    = $conn->real_escape_string($_POST['category']);

    if (!empty($_FILES['image']['name'])) {
        $image        = basename($_FILES['image']['name']);
        $image_temp   = $_FILES['image']['tmp_name'];
        // Save to image/uploads/ so user side can access via ../image/uploads/
        $upload_dir   = '../image/uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_folder = 'uploads/' . $image;   // DB stores: uploads/filename.jpg
        $save_path    = $upload_dir . $image;  // Physical path: image/uploads/filename.jpg

        if (move_uploaded_file($image_temp, $save_path)) {
            $query = "UPDATE products SET name='$name', price='$price', image='$image_folder', description='$description', stock='$stock', category='$category' WHERE id=$product_id";
        } else {
            $message = '<div class="alert error"><i class="fas fa-times-circle"></i> Failed to upload image.</div>';
        }
    } else {
        $query = "UPDATE products SET name='$name', price='$price', description='$description', stock='$stock', category='$category' WHERE id=$product_id";
    }

    if (!$message && isset($query)) {
        if ($conn->query($query) === TRUE) {
            $message = '<div class="alert success"><i class="fas fa-check-circle"></i> Product updated successfully!</div>';
            // Refresh product data
            $product_result = $conn->query("SELECT * FROM products WHERE id = $product_id");
            $product = $product_result->fetch_assoc();
        } else {
            $message = '<div class="alert error"><i class="fas fa-times-circle"></i> Error: ' . $conn->error . '</div>';
        }
    }
}

$admin_name = "Mumma's Care";
$admin_profile_image = "OIP (1).jpg";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product – Admin Panel</title>
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
        .alert { padding:13px 18px; border-radius:10px; margin-bottom:22px; font-size:14px; font-weight:500; display:flex; align-items:center; gap:10px; }
        .alert.success { background:#d1fae5; color:#065f46; }
        .alert.error   { background:#fee2e2; color:#991b1b; }

        /* LAYOUT */
        .update-grid { display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start; }

        /* PANEL */
        .panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; }
        .panel-header { padding:18px 24px; border-bottom:1px solid #f0f2f5; display:flex; align-items:center; gap:10px; }
        .panel-header h3 { font-size:15px; font-weight:600; color:#1a1a2e; }

        /* FORM */
        .update-form { padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group { display:flex; flex-direction:column; gap:6px; }
        .form-group.full { grid-column:1/-1; }
        .form-group label { font-size:13px; font-weight:600; color:#555; }
        .form-group input,
        .form-group textarea,
        .form-group select { padding:10px 14px; border:1px solid #e0e0e0; border-radius:10px; font-size:14px; font-family:'Inter',sans-serif; outline:none; transition:border 0.2s; background:#fff; }
        .form-group input:focus,
        .form-group textarea:focus { border-color:#e91e8c; }
        .form-group textarea { resize:vertical; min-height:100px; }
        .form-actions { grid-column:1/-1; display:flex; gap:12px; }

        .btn { display:inline-flex; align-items:center; gap:8px; padding:11px 24px; border:none; border-radius:10px; font-size:14px; font-weight:600; font-family:'Inter',sans-serif; cursor:pointer; text-decoration:none; transition:opacity 0.2s; }
        .btn-pink  { background:linear-gradient(135deg,#e91e8c,#c2185b); color:#fff; }
        .btn-gray  { background:#f0f2f5; color:#555; }
        .btn:hover { opacity:0.88; }

        /* PREVIEW CARD */
        .preview-card { padding:24px; text-align:center; }
        .preview-img { width:100%; max-height:220px; object-fit:cover; border-radius:12px; margin-bottom:16px; border:1px solid #f0f2f5; }
        .preview-name { font-size:16px; font-weight:600; color:#1a1a2e; margin-bottom:6px; }
        .preview-cat  { font-size:12px; color:#e91e8c; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; background:#fce4ec; padding:4px 12px; border-radius:20px; display:inline-block; margin-bottom:10px; }
        .preview-price { font-size:22px; font-weight:700; color:#1a1a2e; }
        .preview-stock { font-size:13px; color:#888; margin-top:4px; }

        /* FILE INPUT */
        .file-label { display:flex; align-items:center; gap:10px; padding:10px 14px; border:2px dashed #e0e0e0; border-radius:10px; cursor:pointer; font-size:13px; color:#888; transition:border 0.2s; }
        .file-label:hover { border-color:#e91e8c; color:#e91e8c; }
        .file-label input[type="file"] { display:none; }

        @media(max-width:900px) { .update-grid { grid-template-columns:1fr; } }
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
            <h1>Update Product</h1>
            <p>Edit product details — ID #<?= $product_id ?></p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date"><i class="fas fa-calendar-alt" style="margin-right:6px;color:#e91e8c;"></i><?= date('D, d M Y') ?></span>
            <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="topbar-avatar">
        </div>
    </div>

    <div class="page-body">
        <?= $message ?>

        <div class="update-grid">
            <!-- Form -->
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-edit" style="color:#e91e8c;"></i>
                    <h3>Product Details</h3>
                </div>
                <form method="POST" enctype="multipart/form-data" class="update-form" id="updateForm">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" id="fName" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="text" name="price" id="fPrice" value="<?= htmlspecialchars($product['price']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" id="fCat" value="<?= htmlspecialchars($product['category']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
                    </div>
                    <div class="form-group full">
                        <label>Description</label>
                        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    <div class="form-group full">
                        <label>Replace Image <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                        <label class="file-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="fileName">Choose new image…</span>
                            <input type="file" name="image" accept="image/*" onchange="previewImg(this)">
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-pink"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="product.php" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </form>
            </div>

            <!-- Preview Card -->
            <div class="panel">
                <div class="panel-header">
                    <i class="fas fa-eye" style="color:#e91e8c;"></i>
                    <h3>Preview</h3>
                </div>
                <div class="preview-card">
                    <img id="previewImage" src="<?= htmlspecialchars($product['image']) ?>" alt="Product" class="preview-img">
                    <div class="preview-cat" id="prevCat"><?= htmlspecialchars($product['category']) ?></div>
                    <div class="preview-name" id="prevName"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="preview-price" id="prevPrice">₹<?= number_format($product['price'], 2) ?></div>
                    <div class="preview-stock">Stock: <?= htmlspecialchars($product['stock']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live preview updates
document.getElementById('fName').addEventListener('input', e => document.getElementById('prevName').textContent = e.target.value);
document.getElementById('fPrice').addEventListener('input', e => document.getElementById('prevPrice').textContent = '₹' + parseFloat(e.target.value || 0).toFixed(2));
document.getElementById('fCat').addEventListener('input', e => document.getElementById('prevCat').textContent = e.target.value);

function previewImg(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('fileName').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => document.getElementById('previewImage').src = e.target.result;
    reader.readAsDataURL(file);
}
</script>

</body>
</html>
