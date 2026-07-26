<?php
session_start();
if (!isset($_GET['order_id'])) { header("Location: home.php"); exit; }
$order_id = (int)$_GET['order_id'];

$pdo = new PDO("mysql:host=localhost;dbname=mumma's_care", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) { die("Order not found"); }

$stmt = $pdo->prepare("SELECT oi.quantity, oi.price, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$payment_id = isset($_GET['razorpay_payment_id']) ? $_GET['razorpay_payment_id'] : ($order['razorpay_payment_id'] ?? '');
$is_online   = str_starts_with($payment_id, 'FAKE_PAY_') || (!empty($payment_id) && $payment_id !== '');
$method_label = $order['payment_method'] === 'COD' ? 'Cash on Delivery' : 'Online Payment';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Placed - Mumma's Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#f5f0ff;min-height:100vh}

.page-wrap{max-width:680px;margin:36px auto;padding:0 16px 60px}

/* ── Confetti banner ── */
.banner{background:linear-gradient(135deg,#e91e63,#9c27b0);border-radius:20px;padding:36px 24px;text-align:center;color:#fff;position:relative;overflow:hidden;margin-bottom:24px}
.banner::before,.banner::after{content:'';position:absolute;border-radius:50%;opacity:.15}
.banner::before{width:220px;height:220px;background:#fff;top:-60px;left:-60px}
.banner::after{width:160px;height:160px;background:#fff;bottom:-40px;right:-40px}
.checkmark{width:72px;height:72px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 4px 20px rgba(0,0,0,.2)}
.checkmark i{font-size:34px;color:#e91e63}
.banner h1{font-size:24px;font-weight:800;margin-bottom:6px;position:relative}
.banner p{font-size:14px;opacity:.9;position:relative}

/* ── Steps tracker ── */
.steps{display:flex;align-items:center;justify-content:center;gap:0;margin-bottom:24px;background:#fff;border-radius:14px;padding:20px 16px;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.step{display:flex;flex-direction:column;align-items:center;gap:6px;flex:1}
.step-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px}
.step.done .step-icon{background:#e91e63;color:#fff}
.step.pending .step-icon{background:#f3e5f5;color:#9c27b0}
.step-label{font-size:11px;font-weight:600;color:#666;text-align:center}
.step-line{flex:1;height:3px;background:#f3e5f5;border-radius:2px;margin-bottom:20px}
.step-line.done{background:#e91e63}

/* ── Cards ── */
.card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.07);margin-bottom:16px}
.card-title{font-size:14px;font-weight:700;color:#333;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.card-title i{color:#e91e63}

/* Order meta */
.meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.meta-item{background:#fdf2f8;border-radius:10px;padding:12px 14px}
.meta-item .label{font-size:11px;color:#999;margin-bottom:3px;text-transform:uppercase;letter-spacing:.5px}
.meta-item .value{font-size:14px;font-weight:700;color:#333}
.meta-item .value.pink{color:#e91e63}

/* Items */
.order-item{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f5f5f5}
.order-item:last-child{border-bottom:none}
.order-item img{width:56px;height:56px;object-fit:cover;border-radius:10px;border:1px solid #eee}
.order-item .info{flex:1}
.order-item .info .name{font-size:14px;font-weight:600;color:#333}
.order-item .info .qty{font-size:12px;color:#999;margin-top:2px}
.order-item .price{font-size:14px;font-weight:700;color:#222}

/* Total row */
.total-row{display:flex;justify-content:space-between;align-items:center;padding-top:12px;margin-top:4px;border-top:2px dashed #f0e0f8}
.total-row span:first-child{font-size:15px;font-weight:700;color:#333}
.total-row span:last-child{font-size:20px;font-weight:800;color:#e91e63}

/* Address */
.address-box{background:#fdf2f8;border-radius:10px;padding:14px;font-size:14px;color:#444;line-height:1.7}

/* Buttons */
.btn-row{display:flex;gap:12px;margin-top:20px;flex-wrap:wrap}
.btn{flex:1;padding:13px;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;transition:transform .15s,opacity .2s}
.btn:hover{opacity:.88;transform:translateY(-1px)}
.btn-primary{background:linear-gradient(135deg,#e91e63,#9c27b0);color:#fff}
.btn-outline{background:#fff;color:#e91e63;border:2px solid #e91e63}

/* Animate in */
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
.page-wrap > *{animation:fadeUp .5s ease both}
.page-wrap > *:nth-child(2){animation-delay:.1s}
.page-wrap > *:nth-child(3){animation-delay:.2s}
.page-wrap > *:nth-child(4){animation-delay:.3s}
.page-wrap > *:nth-child(5){animation-delay:.4s}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="page-wrap">

  <!-- Banner -->
  <div class="banner">
    <div class="checkmark"><i class="fa fa-check"></i></div>
    <h1>Order Placed Successfully!</h1>
    <p>Thank you, <strong><?= htmlspecialchars($order['name']) ?></strong>! Your order is confirmed.</p>
  </div>

  <!-- Steps -->
  <div class="steps">
    <div class="step done">
      <div class="step-icon"><i class="fa fa-check"></i></div>
      <div class="step-label">Order<br>Placed</div>
    </div>
    <div class="step-line done"></div>
    <div class="step pending">
      <div class="step-icon"><i class="fa fa-box"></i></div>
      <div class="step-label">Being<br>Packed</div>
    </div>
    <div class="step-line pending"></div>
    <div class="step pending">
      <div class="step-icon"><i class="fa fa-truck"></i></div>
      <div class="step-label">Out for<br>Delivery</div>
    </div>
    <div class="step-line pending"></div>
    <div class="step pending">
      <div class="step-icon"><i class="fa fa-home"></i></div>
      <div class="step-label">Delivered</div>
    </div>
  </div>

  <!-- Order Meta -->
  <div class="card">
    <div class="card-title"><i class="fa fa-receipt"></i> Order Details</div>
    <div class="meta-grid">
      <div class="meta-item">
        <div class="label">Order ID</div>
        <div class="value pink">#<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></div>
      </div>
      <div class="meta-item">
        <div class="label">Date</div>
        <div class="value"><?= date('d M Y', strtotime($order['created_at'] ?? 'now')) ?></div>
      </div>
      <div class="meta-item">
        <div class="label">Payment</div>
        <div class="value"><?= $method_label ?></div>
      </div>
      <div class="meta-item">
        <div class="label">Status</div>
        <div class="value pink"><?= ucfirst($order['order_status']) ?></div>
      </div>
    </div>
  </div>

  <!-- Items -->
  <div class="card">
    <div class="card-title"><i class="fa fa-shopping-bag"></i> Items Ordered</div>
    <?php foreach ($items as $item): ?>
    <div class="order-item">
      <img src="../image/<?= htmlspecialchars($item['image']) ?>" alt="">
      <div class="info">
        <div class="name"><?= htmlspecialchars($item['name']) ?></div>
        <div class="qty">Qty: <?= $item['quantity'] ?></div>
      </div>
      <div class="price">₹<?= number_format($item['price'] * $item['quantity']) ?></div>
    </div>
    <?php endforeach; ?>
    <div class="total-row">
      <span>Total Amount</span>
      <span>₹<?= number_format($order['total_price']) ?></span>
    </div>
  </div>

  <!-- Delivery Address -->
  <div class="card">
    <div class="card-title"><i class="fa fa-map-marker-alt"></i> Delivery Address</div>
    <div class="address-box">
      <strong><?= htmlspecialchars($order['name']) ?></strong><br>
      <?= nl2br(htmlspecialchars($order['address'])) ?><br>
      <i class="fa fa-phone" style="color:#e91e63"></i> <?= htmlspecialchars($order['contact_number']) ?>
    </div>
  </div>

  <!-- Buttons -->
  <div class="btn-row">
    <a href="home.php" class="btn btn-primary"><i class="fa fa-home"></i> Continue Shopping</a>
    <a href="my_order.php" class="btn btn-outline"><i class="fa fa-box"></i> My Orders</a>
  </div>

</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
