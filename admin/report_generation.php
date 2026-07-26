<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'];

    if ($report_type == 'customer') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=customer_report.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Email', 'Registered At']);
        $result = $conn->query("SELECT * FROM customers");
        if ($result) while ($row = $result->fetch_assoc()) fputcsv($output, $row);
        fclose($output); exit();
    }

    if ($report_type == 'order') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=order_report.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Order ID', 'Name', 'Email', 'Address', 'Total Price', 'Payment Method', 'Order Date']);
        $result = $conn->query("SELECT * FROM orders");
        if ($result) while ($row = $result->fetch_assoc()) fputcsv($output, $row);
        fclose($output); exit();
    }

    if ($report_type == 'product') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=product_report.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product ID', 'Name', 'Category', 'Description', 'Price', 'Stock']);
        $result = $conn->query("SELECT * FROM products");
        if ($result) while ($row = $result->fetch_assoc()) fputcsv($output, $row);
        fclose($output); exit();
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
    <title>Reports – Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f0f2f5; display:flex; min-height:100vh; color:#333; }

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

        .main-content { margin-left:260px; flex:1; display:flex; flex-direction:column; }
        .topbar { background:#fff; padding:0 32px; height:68px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 8px rgba(0,0,0,0.06); position:sticky; top:0; z-index:90; }
        .topbar h1 { font-size:22px; font-weight:700; color:#1a1a2e; }
        .topbar p { font-size:13px; color:#888; margin-top:2px; }
        .topbar-right { display:flex; align-items:center; gap:16px; }
        .topbar-date { font-size:13px; color:#888; background:#f0f2f5; padding:7px 14px; border-radius:20px; }
        .topbar-avatar { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #e91e8c; }
        .page-body { padding:28px 32px; }

        .panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; max-width:600px; }
        .panel-header { padding:18px 24px; border-bottom:1px solid #f0f2f5; }
        .panel-header h3 { font-size:15px; font-weight:600; color:#1a1a2e; }
        .report-form { padding:28px 24px; display:flex; flex-direction:column; gap:20px; }
        .form-group { display:flex; flex-direction:column; gap:8px; }
        .form-group label { font-size:13px; font-weight:600; color:#555; }
        .form-group select { padding:11px 14px; border:1px solid #e0e0e0; border-radius:10px; font-size:14px; font-family:'Inter',sans-serif; outline:none; transition:border 0.2s; background:#fff; cursor:pointer; }
        .form-group select:focus { border-color:#e91e8c; }
        .report-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:28px; }
        .report-card { background:#fff; border-radius:14px; padding:20px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.06); border-top:3px solid transparent; }
        .report-card.c1 { border-top-color:#3b82f6; }
        .report-card.c2 { border-top-color:#10b981; }
        .report-card.c3 { border-top-color:#f59e0b; }
        .report-card i { font-size:28px; margin-bottom:10px; display:block; }
        .report-card.c1 i { color:#3b82f6; }
        .report-card.c2 i { color:#10b981; }
        .report-card.c3 i { color:#f59e0b; }
        .report-card p { font-size:13px; font-weight:600; color:#555; }
        .btn-pink { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg,#e91e8c,#c2185b); color:#fff; border:none; border-radius:10px; font-size:14px; font-weight:600; font-family:'Inter',sans-serif; cursor:pointer; transition:opacity 0.2s; }
        .btn-pink:hover { opacity:0.9; }
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
        <a href="report_generation.php" class="active"><i class="fas fa-chart-bar"></i><span>Reports</span></a>
        <a href="customer.php"><i class="fas fa-users"></i><span>Customers</span></a>
        <a href="product.php"><i class="fas fa-box-open"></i><span>Products</span></a>
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
            <h1>Reports</h1>
            <p>Generate and download CSV reports</p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date"><i class="fas fa-calendar-alt" style="margin-right:6px;color:#e91e8c;"></i><?= date('D, d M Y') ?></span>
            <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="topbar-avatar">
        </div>
    </div>

    <div class="page-body">

        <!-- Info Cards -->
        <div class="report-cards">
            <div class="report-card c1">
                <i class="fas fa-users"></i>
                <p>Customer Report</p>
            </div>
            <div class="report-card c2">
                <i class="fas fa-box-open"></i>
                <p>Product Report</p>
            </div>
            <div class="report-card c3">
                <i class="fas fa-shopping-bag"></i>
                <p>Order Report</p>
            </div>
        </div>

        <!-- Generate Form -->
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-file-download" style="color:#e91e8c;margin-right:8px;"></i>Generate Report</h3>
            </div>
            <form action="report_generation.php" method="POST" class="report-form">
                <div class="form-group">
                    <label for="report_type">Select Report Type</label>
                    <select name="report_type" id="report_type">
                        <option value="customer">Customer Report</option>
                        <option value="product">Product Report</option>
                        <option value="order">Order Report</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="generate_report" class="btn-pink">
                        <i class="fas fa-download"></i> Download CSV
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>
