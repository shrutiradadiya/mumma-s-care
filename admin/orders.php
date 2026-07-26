<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$orders_result = $conn->query("SELECT * FROM orders");

$admin_name = "Mumma's Care";
$admin_profile_image = "OIP (1).jpg";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders – Admin Panel</title>
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

        .panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; }
        .panel-header { padding:18px 24px; border-bottom:1px solid #f0f2f5; }
        .panel-header h3 { font-size:15px; font-weight:600; color:#1a1a2e; }

        .table-wrap { overflow-x:auto; }
        .data-table { width:100%; border-collapse:collapse; min-width:900px; }
        .data-table th { background:#f8fafc; padding:12px 16px; font-size:12px; font-weight:600; color:#888; text-transform:uppercase; letter-spacing:0.5px; text-align:left; white-space:nowrap; }
        .data-table td { padding:12px 16px; font-size:13px; border-bottom:1px solid #f0f2f5; color:#444; }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:#fafafa; }
        .btn-delete { background:#fee2e2; color:#991b1b; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:12px; font-weight:600; }
        .btn-delete:hover { background:#fecaca; }
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
        <a href="product.php"><i class="fas fa-box-open"></i><span>Products</span></a>
        <a href="orders.php" class="active"><i class="fas fa-shopping-bag"></i><span>Orders</span></a>
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
            <h1>Orders</h1>
            <p>All orders &amp; payment details</p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date"><i class="fas fa-calendar-alt" style="margin-right:6px;color:#e91e8c;"></i><?= date('D, d M Y') ?></span>
            <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="topbar-avatar">
        </div>
    </div>

    <div class="page-body">
        <div class="panel">
            <div class="panel-header">
                <h3><i class="fas fa-shopping-bag" style="color:#e91e8c;margin-right:8px;"></i>Order List</h3>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Total Price</th>
                            <th>Contact No</th>
                            <th>Payment Method</th>
                            <th>Razorpay ID</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                            <?php while ($row = $orders_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?= htmlspecialchars($row['id']) ?></strong></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td>₹<?= number_format($row['total_price'], 2) ?></td>
                                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                <td><?= htmlspecialchars($row['payment_method']) ?></td>
                                <td><?= htmlspecialchars($row['razorpay_payment_id']) ?></td>
                                <td><?= date('d M Y', strtotime($row['order_date'])) ?></td>
                                <td>
                                    <a href="delete-orders.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this order?')" class="btn-delete">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="10" style="text-align:center;padding:30px;color:#aaa;">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
