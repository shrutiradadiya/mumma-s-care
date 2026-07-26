<?php
session_start();

if (!isset($_GET['order_id'])) {
    header("Location: home.php"); exit;
}
$order_id = (int)$_GET['order_id'];

$pdo = new PDO("mysql:host=localhost;dbname=mumma's_care", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle fake payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $fake_payment_id = 'FAKE_PAY_' . strtoupper(uniqid());

    // Update order status
    $stmt = $pdo->prepare("UPDATE orders SET order_status='Paid', razorpay_payment_id=? WHERE id=?");
    $stmt->execute([$fake_payment_id, $order_id]);

    // Insert into payment table if exists (ignore error if table missing)
    try {
        $stmt = $pdo->prepare("INSERT INTO payment (order_id, razorpay_payment_id, status) VALUES (?,?,'Success') ON DUPLICATE KEY UPDATE razorpay_payment_id=?, status='Success'");
        $stmt->execute([$order_id, $fake_payment_id, $fake_payment_id]);
    } catch (Exception $e) {}

    header("Location: order_success.php?order_id=$order_id&razorpay_payment_id=$fake_payment_id");
    exit;
}

// Fetch order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) { die("Order not found."); }

// Fetch items
$stmt = $pdo->prepare("SELECT oi.quantity, p.name, p.price, p.image FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $item) $total += $item['price'] * $item['quantity'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment - Mumma's Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#f0f2f5;min-height:100vh}

.pay-wrap{display:flex;gap:24px;max-width:900px;margin:30px auto;padding:0 16px;flex-wrap:wrap}

/* Order summary */
.order-summary{flex:1;min-width:260px;background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.08);height:fit-content}
.order-summary h3{font-size:16px;margin-bottom:14px;color:#333;border-bottom:1px solid #eee;padding-bottom:8px}
.order-item{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.order-item img{width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #eee}
.order-item .item-info{flex:1}
.order-item .item-name{font-size:13px;font-weight:600;color:#333}
.order-item .item-qty{font-size:12px;color:#888}
.order-item .item-price{font-size:13px;font-weight:700;color:#222;white-space:nowrap}
.order-total{border-top:1px solid #eee;padding-top:12px;margin-top:4px;display:flex;justify-content:space-between;font-size:15px;font-weight:700}
.order-total span:last-child{color:#e53935}

/* Payment box */
.pay-box{flex:1.4;min-width:300px;background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.pay-box h2{font-size:18px;margin-bottom:18px;color:#222}

/* Tabs */
.tabs{display:flex;gap:0;border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;margin-bottom:20px}
.tab{flex:1;padding:10px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;background:#f9f9f9;color:#666;border:none;transition:background .2s,color .2s}
.tab.active{background:#e91e63;color:#fff}

/* Tab panels */
.tab-panel{display:none}
.tab-panel.active{display:block}

/* Form */
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;color:#666;margin-bottom:5px;font-weight:600}
.form-group input,.form-group select{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:14px;outline:none;transition:border .2s}
.form-group input:focus,.form-group select:focus{border-color:#e91e63}
.row2{display:flex;gap:12px}
.row2 .form-group{flex:1}

/* Card preview */
.card-preview{background:linear-gradient(135deg,#e91e63,#9c27b0);border-radius:12px;padding:18px 20px;color:#fff;margin-bottom:18px;position:relative;min-height:90px}
.card-preview .card-number{font-size:17px;letter-spacing:3px;margin-bottom:10px;font-family:monospace}
.card-preview .card-bottom{display:flex;justify-content:space-between;font-size:12px;opacity:.85}
.card-preview .chip{width:32px;height:24px;background:rgba(255,255,255,.3);border-radius:4px;margin-bottom:10px}

/* UPI */
.upi-logos{display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.upi-logo{border:2px solid #eee;border-radius:8px;padding:8px 14px;cursor:pointer;font-size:13px;font-weight:700;transition:border .2s}
.upi-logo.selected{border-color:#e91e63;color:#e91e63}
.upi-logo img{height:28px;display:block}

/* Net banking */
.bank-list{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px}
.bank-item{border:1px solid #eee;border-radius:8px;padding:10px;cursor:pointer;font-size:13px;font-weight:600;text-align:center;transition:border .2s,background .2s}
.bank-item:hover,.bank-item.selected{border-color:#e91e63;background:#fce4ec;color:#e91e63}

/* Pay button */
.btn-pay{width:100%;padding:13px;background:#e91e63;color:#fff;border:none;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;transition:background .2s;margin-top:6px}
.btn-pay:hover{background:#c2185b}

/* Processing overlay */
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center;flex-direction:column;gap:16px}
.overlay.show{display:flex}
.spinner{width:56px;height:56px;border:5px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.overlay p{color:#fff;font-size:16px;font-weight:600}

.secure-note{display:flex;align-items:center;gap:6px;font-size:12px;color:#888;margin-top:12px;justify-content:center}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="pay-wrap">

  <!-- Order Summary -->
  <div class="order-summary">
    <h3><i class="fa fa-receipt"></i> Order Summary</h3>
    <?php foreach ($items as $item): ?>
    <div class="order-item">
      <img src="../image/<?= htmlspecialchars($item['image']) ?>" alt="">
      <div class="item-info">
        <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
        <div class="item-qty">Qty: <?= $item['quantity'] ?></div>
      </div>
      <div class="item-price">₹<?= number_format($item['price'] * $item['quantity']) ?></div>
    </div>
    <?php endforeach; ?>
    <div class="order-total">
      <span>Total</span>
      <span>₹<?= number_format($total) ?></span>
    </div>
  </div>

  <!-- Payment Box -->
  <div class="pay-box">
    <h2><i class="fa fa-lock" style="color:#e91e63"></i> Secure Payment</h2>

    <!-- Tabs -->
    <div class="tabs">
      <button class="tab active" onclick="switchTab('card',this)"><i class="fa fa-credit-card"></i> Card</button>
      <button class="tab" onclick="switchTab('upi',this)"><i class="fa fa-mobile-alt"></i> UPI</button>
      <button class="tab" onclick="switchTab('netbank',this)"><i class="fa fa-university"></i> Net Banking</button>
    </div>

    <form method="POST" onsubmit="processPayment(event)">
      <input type="hidden" name="pay" value="1">

      <!-- Card Panel -->
      <div class="tab-panel active" id="panel-card">
        <div class="card-preview">
          <div class="chip"></div>
          <div class="card-number" id="previewNum">•••• •••• •••• ••••</div>
          <div class="card-bottom">
            <span id="previewName">CARD HOLDER</span>
            <span id="previewExp">MM/YY</span>
          </div>
        </div>
        <div class="form-group">
          <label>Card Number</label>
          <input type="text" placeholder="1234 5678 9012 3456" maxlength="19"
            oninput="formatCard(this)" onkeyup="document.getElementById('previewNum').textContent=this.value||'•••• •••• •••• ••••'">
        </div>
        <div class="form-group">
          <label>Cardholder Name</label>
          <input type="text" placeholder="Name on card"
            oninput="document.getElementById('previewName').textContent=this.value.toUpperCase()||'CARD HOLDER'">
        </div>
        <div class="row2">
          <div class="form-group">
            <label>Expiry</label>
            <input type="text" placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)"
              onkeyup="document.getElementById('previewExp').textContent=this.value||'MM/YY'">
          </div>
          <div class="form-group">
            <label>CVV</label>
            <input type="password" placeholder="•••" maxlength="3">
          </div>
        </div>
      </div>

      <!-- UPI Panel -->
      <div class="tab-panel" id="panel-upi">
        <div class="upi-logos">
          <div class="upi-logo selected" onclick="selectUpi(this)">GPay</div>
          <div class="upi-logo" onclick="selectUpi(this)">PhonePe</div>
          <div class="upi-logo" onclick="selectUpi(this)">Paytm</div>
          <div class="upi-logo" onclick="selectUpi(this)">BHIM</div>
        </div>
        <div class="form-group">
          <label>UPI ID</label>
          <input type="text" placeholder="yourname@upi">
        </div>
      </div>

      <!-- Net Banking Panel -->
      <div class="tab-panel" id="panel-netbank">
        <div class="bank-list">
          <div class="bank-item selected" onclick="selectBank(this)">SBI</div>
          <div class="bank-item" onclick="selectBank(this)">HDFC</div>
          <div class="bank-item" onclick="selectBank(this)">ICICI</div>
          <div class="bank-item" onclick="selectBank(this)">Axis</div>
          <div class="bank-item" onclick="selectBank(this)">Kotak</div>
          <div class="bank-item" onclick="selectBank(this)">PNB</div>
        </div>
        <div class="form-group">
          <label>User ID</label>
          <input type="text" placeholder="Net banking user ID">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" placeholder="Password">
        </div>
      </div>

      <button type="submit" class="btn-pay">
        <i class="fa fa-lock"></i> Pay ₹<?= number_format($total) ?>
      </button>
    </form>

    <div class="secure-note">
      <i class="fa fa-shield-alt" style="color:#4caf50"></i>
      100% Secure &amp; Encrypted Payment
    </div>
  </div>
</div>

<!-- Processing Overlay -->
<div class="overlay" id="overlay">
  <div class="spinner"></div>
  <p>Processing your payment...</p>
</div>

<?php include "../includes/footer.php"; ?>

<script>
function switchTab(name, btn) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel-' + name).classList.add('active');
}

function formatCard(input) {
  let v = input.value.replace(/\D/g,'').substring(0,16);
  input.value = v.replace(/(.{4})/g,'$1 ').trim();
}

function formatExpiry(input) {
  let v = input.value.replace(/\D/g,'');
  if (v.length >= 2) v = v.substring(0,2) + '/' + v.substring(2,4);
  input.value = v;
}

function selectUpi(el) {
  document.querySelectorAll('.upi-logo').forEach(u => u.classList.remove('selected'));
  el.classList.add('selected');
}

function selectBank(el) {
  document.querySelectorAll('.bank-item').forEach(b => b.classList.remove('selected'));
  el.classList.add('selected');
}

function processPayment(e) {
  e.preventDefault();
  document.getElementById('overlay').classList.add('show');
  // Simulate 2.5s processing delay then submit
  setTimeout(() => { e.target.submit(); }, 2500);
}
</script>
</body>
</html>
