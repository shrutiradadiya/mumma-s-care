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

$total_customer = $conn->query("SELECT COUNT(*) AS count FROM customers")->fetch_assoc()['count'] ?? 0;
$total_products = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'] ?? 0;
$total_orders   = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'] ?? 0;
$total_order_items = $conn->query("SELECT COUNT(*) AS count FROM order_items")->fetch_assoc()['count'] ?? 0;
$total_contact_form = $conn->query("SELECT COUNT(*) AS count FROM contact_form")->fetch_assoc()['count'] ?? 0;

// Recent orders
$recent_orders_result = $conn->query("SELECT o.id, c.name, o.total_amount, o.status, o.created_at FROM orders o JOIN customers c ON o.customer_id = c.id ORDER BY o.created_at DESC LIMIT 5");

// Orders per month (last 6 months) for bar chart
$orders_monthly_result = $conn->query("
    SELECT DATE_FORMAT(order_date, '%b %Y') AS month_label,
           DATE_FORMAT(order_date, '%Y-%m') AS month_sort,
           COUNT(*) AS total
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month_sort, month_label
    ORDER BY month_sort ASC
");
$chart_labels = [];
$chart_data   = [];
if ($orders_monthly_result) {
    while ($r = $orders_monthly_result->fetch_assoc()) {
        $chart_labels[] = $r['month_label'];
        $chart_data[]   = (int)$r['total'];
    }
}

// Products by category for pie chart
$cat_result = $conn->query("SELECT category, COUNT(*) AS total FROM products GROUP BY category ORDER BY total DESC LIMIT 6");
$cat_labels = [];
$cat_data   = [];
if ($cat_result) {
    while ($r = $cat_result->fetch_assoc()) {
        $cat_labels[] = $r['category'];
        $cat_data[]   = (int)$r['total'];
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
    <title>Admin Dashboard – Mumma's Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            display: flex;
            min-height: 100vh;
            color: #333;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.3);
            transition: width 0.3s ease;
        }

        .sidebar-header {
            padding: 28px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            text-align: center;
        }

        .sidebar-logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e91e8c;
            margin-bottom: 12px;
        }

        .sidebar-header h3 {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .sidebar-header span {
            font-size: 12px;
            color: #e91e8c;
            font-weight: 500;
        }

        .sidebar nav {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            padding: 14px 12px 6px;
            font-weight: 600;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 3px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar nav a i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: rgba(233,30,140,0.2);
            color: #fff;
        }

        .sidebar nav a.active {
            background: linear-gradient(135deg, #e91e8c, #c2185b);
            color: #fff;
            box-shadow: 0 4px 12px rgba(233,30,140,0.4);
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar-footer a:hover {
            background: rgba(231,76,60,0.2);
            color: #e74c3c;
        }

        /* ── MAIN ── */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: #fff;
            padding: 0 32px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-left h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .topbar-left p {
            font-size: 13px;
            color: #888;
            margin-top: 2px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-date {
            font-size: 13px;
            color: #888;
            background: #f0f2f5;
            padding: 7px 14px;
            border-radius: 20px;
        }

        .topbar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e91e8c;
            cursor: pointer;
        }

        /* ── PAGE BODY ── */
        .page-body {
            padding: 28px 32px;
            flex: 1;
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-left: 4px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .stat-card.c1 { border-left-color: #3b82f6; }
        .stat-card.c2 { border-left-color: #10b981; }
        .stat-card.c3 { border-left-color: #f59e0b; }
        .stat-card.c4 { border-left-color: #8b5cf6; }
        .stat-card.c5 { border-left-color: #e91e8c; }

        .stat-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-card.c1 .stat-icon { background: #eff6ff; color: #3b82f6; }
        .stat-card.c2 .stat-icon { background: #ecfdf5; color: #10b981; }
        .stat-card.c3 .stat-icon { background: #fffbeb; color: #f59e0b; }
        .stat-card.c4 .stat-icon { background: #f5f3ff; color: #8b5cf6; }
        .stat-card.c5 .stat-icon { background: #fce4ec; color: #e91e8c; }

        .stat-info h2 {
            font-size: 30px;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1;
        }

        .stat-info p {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
            font-weight: 500;
        }

        /* ── BOTTOM GRID ── */
        .bottom-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-header h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .panel-header a {
            font-size: 13px;
            color: #e91e8c;
            text-decoration: none;
            font-weight: 500;
        }

        /* ── RECENT ORDERS TABLE ── */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th {
            background: #f8fafc;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .orders-table td {
            padding: 14px 20px;
            font-size: 14px;
            border-bottom: 1px solid #f0f2f5;
            color: #444;
        }

        .orders-table tr:last-child td { border-bottom: none; }

        .orders-table tr:hover td { background: #fafafa; }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge.pending   { background: #fff3cd; color: #856404; }
        .badge.completed { background: #d1fae5; color: #065f46; }
        .badge.cancelled { background: #fee2e2; color: #991b1b; }
        .badge.shipped   { background: #dbeafe; color: #1e40af; }
        .badge.processing{ background: #ede9fe; color: #5b21b6; }

        .no-orders {
            padding: 40px;
            text-align: center;
            color: #aaa;
            font-size: 14px;
        }

        /* ── QUICK LINKS ── */
        .quick-links {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-link-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .quick-link-item:hover {
            background: linear-gradient(135deg, #e91e8c, #c2185b);
            color: #fff;
        }

        .quick-link-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .quick-link-item span {
            flex: 1;
        }

        .quick-link-item .arrow {
            font-size: 12px;
            opacity: 0.5;
        }

        /* ── CHARTS GRID ── */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .chart-panel {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .chart-body {
            padding: 20px 24px 24px;
            position: relative;
            height: 280px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .bottom-grid { grid-template-columns: 1fr; }
            .charts-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar-header h3,
            .sidebar-header span,
            .nav-label,
            .sidebar nav a span,
            .sidebar-footer a span { display: none; }
            .sidebar nav a { justify-content: center; padding: 12px; }
            .sidebar-logo { width: 44px; height: 44px; }
            .main-content { margin-left: 70px; }
            .page-body { padding: 20px 16px; }
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR ── -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="sidebar-logo">
        <h3><?= htmlspecialchars($admin_name) ?></h3>
        <span>Administrator</span>
    </div>

    <nav>
        <a href="dashboard.php" class="active">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="report_generation.php">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>
        <a href="customer.php">
            <i class="fas fa-users"></i>
            <span>Customers</span>
        </a>
        <a href="product.php">
            <i class="fas fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="orders.php">
            <i class="fas fa-shopping-bag"></i>
            <span>Orders</span>
        </a>
        <a href="order_items.php">
            <i class="fas fa-list-ul"></i>
            <span>Order Items</span>
        </a>
        <a href="contacts.php">
            <i class="fas fa-envelope"></i>
            <span>Contacts</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<!-- ── MAIN CONTENT ── -->
<div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <h1>Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($admin_name) ?></p>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">
                <i class="fas fa-calendar-alt" style="margin-right:6px;color:#e91e8c;"></i>
                <?= date('D, d M Y') ?>
            </span>
            <img src="<?= htmlspecialchars($admin_profile_image) ?>" alt="Admin" class="topbar-avatar">
        </div>
    </div>

    <!-- Page Body -->
    <div class="page-body">

        <!-- Stat Cards -->
        <div class="stats-grid">
            <a href="customer.php" class="stat-card c1">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h2><?= $total_customer ?></h2>
                    <p>Customers</p>
                </div>
            </a>
            <a href="product.php" class="stat-card c2">
                <div class="stat-icon"><i class="fas fa-box-open"></i></div>
                <div class="stat-info">
                    <h2><?= $total_products ?></h2>
                    <p>Products</p>
                </div>
            </a>
            <a href="orders.php" class="stat-card c3">
                <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                <div class="stat-info">
                    <h2><?= $total_orders ?></h2>
                    <p>Total Orders</p>
                </div>
            </a>
            <a href="order_items.php" class="stat-card c4">
                <div class="stat-icon"><i class="fas fa-list-ul"></i></div>
                <div class="stat-info">
                    <h2><?= $total_order_items ?></h2>
                    <p>Order Items</p>
                </div>
            </a>
            <a href="contacts.php" class="stat-card c5">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <h2><?= $total_contact_form ?></h2>
                    <p>Messages</p>
                </div>
            </a>
        </div>

        <!-- Charts -->
        <div class="charts-grid">

            <!-- Bar Chart: Orders per Month -->
            <div class="chart-panel">
                <div class="panel-header">
                    <h3><i class="fas fa-chart-bar" style="color:#e91e8c;margin-right:8px;"></i>Orders – Last 6 Months</h3>
                </div>
                <div class="chart-body">
                    <canvas id="ordersBarChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart: Products by Category -->
            <div class="chart-panel">
                <div class="panel-header">
                    <h3><i class="fas fa-chart-pie" style="color:#e91e8c;margin-right:8px;"></i>Products by Category</h3>
                </div>
                <div class="chart-body">
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Bottom Grid -->
        <div class="bottom-grid">

            <!-- Recent Orders -->
            <div class="panel">
                <div class="panel-header">
                    <h3><i class="fas fa-clock" style="color:#e91e8c;margin-right:8px;"></i>Recent Orders</h3>
                    <a href="orders.php">View All →</a>
                </div>
                <?php if ($recent_orders_result && $recent_orders_result->num_rows > 0): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?= $row['id'] ?></strong></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                            <td>
                                <span class="badge <?= strtolower($row['status']) ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="no-orders">
                    <i class="fas fa-inbox" style="font-size:32px;margin-bottom:10px;display:block;"></i>
                    No recent orders found
                </div>
                <?php endif; ?>
            </div>

            <!-- Quick Links -->
            <div class="panel">
                <div class="panel-header">
                    <h3><i class="fas fa-bolt" style="color:#e91e8c;margin-right:8px;"></i>Quick Actions</h3>
                </div>
                <div class="quick-links">
                    <a href="product.php" class="quick-link-item">
                        <i class="fas fa-plus-circle"></i>
                        <span>Manage Products</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <a href="orders.php" class="quick-link-item">
                        <i class="fas fa-shopping-bag"></i>
                        <span>View All Orders</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <a href="customer.php" class="quick-link-item">
                        <i class="fas fa-user-friends"></i>
                        <span>Manage Customers</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <a href="contacts.php" class="quick-link-item">
                        <i class="fas fa-envelope-open-text"></i>
                        <span>View Messages</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <a href="report_generation.php" class="quick-link-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Generate Report</span>
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
<?php
// Pass PHP data to JS
$js_bar_labels = json_encode($chart_labels);
$js_bar_data   = json_encode($chart_data);
$js_pie_labels = json_encode($cat_labels);
$js_pie_data   = json_encode($cat_data);
?>
<script>
// ── BAR CHART: Orders per Month ──
const barCtx = document.getElementById('ordersBarChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?= $js_bar_labels ?>,
        datasets: [{
            label: 'Orders',
            data: <?= $js_bar_data ?>,
            backgroundColor: 'rgba(233,30,140,0.15)',
            borderColor: '#e91e8c',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' Orders: ' + ctx.parsed.y
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, font: { family: 'Inter', size: 12 }, color: '#888' },
                grid: { color: '#f0f2f5' }
            },
            x: {
                ticks: { font: { family: 'Inter', size: 12 }, color: '#888' },
                grid: { display: false }
            }
        }
    }
});

// ── PIE CHART: Products by Category ──
const pieCtx = document.getElementById('categoryPieChart').getContext('2d');
const pieColors = ['#e91e8c','#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444'];
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: <?= $js_pie_labels ?>,
        datasets: [{
            data: <?= $js_pie_data ?>,
            backgroundColor: pieColors,
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { family: 'Inter', size: 12 },
                    color: '#555',
                    padding: 14,
                    usePointStyle: true,
                    pointStyleWidth: 10
                }
            },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' products'
                }
            }
        },
        cutout: '60%'
    }
});
</script>
