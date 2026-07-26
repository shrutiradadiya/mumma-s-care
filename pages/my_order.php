<?php
session_start();
if (!isset($_SESSION['customer_id'])) { header("Location: login.php"); exit; }

$customerId = $_SESSION['customer_id'];
$pdo = new PDO("mysql:host=localhost;dbname=mumma's_care", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id=? ORDER BY id DESC");
$stmt->execute([$customerId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Status config
$statusConfig = [
    'pending'   => ['icon'=>'fa-clock',        'color'=>'#ff9800', 'bg'=>'#fff3e0', 'label'=>'Pending'],
    'paid'      => ['icon'=>'fa-check-circle',  'color'=>'#4caf50', 'bg'=>'#e8f5e9', 'label'=>'Paid'],
    'Paid'      => ['icon'=>'fa-check-circle',  'color'=>'#4caf50', 'bg'=>'#e8f5e9', 'label'=>'Paid'],
    'processing'=> ['icon'=>'fa-box',           'color'=>'#2196f3', 'bg'=>'#e3f2fd', 'label'=>'Processing'],
    'shipped'   => ['icon'=>'fa-truck',         'color'=>'#9c27b0', 'bg'=>'#f3e5f5', 'label'=>'Shipped'],
    'completed' => ['icon'=>'fa-check-double',  'color'=>'#388e3c', 'bg'=>'#e8f5e9', 'label'=>'Completed'],
    'cancelled' => ['icon'=>'fa-times-circle',  'color'=>'#e53935', 'bg'=>'#ffebee', 'label'=>'Cancelled'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders - Mumma's Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#f5f0ff;min-height:100vh}

.page-wrap{max-width:820px;margin:30px auto;padding:0 16px 60px}

/* Page header */
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:10px}
.page-header h1{font-size:22px;font-weight:800;color:#333}
.page-header h1 span{color:#e91e63}
.order-count{background:#fce4ec;color:#e91e63;font-size:13px;font-weight:700;padding:5px 14px;border-radius:20px}

/* Alert */
.alert{padding:12px 16px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:600}
.alert-success{background:#e8f5e9;color:#388e3c}
.alert-error{background:#ffebee;color:#e53935}

/* Order card */
.order-card{background:#fff;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,.08);margin-bottom:20px;overflow:hidden;transition:box-shadow .2s}
.order-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.12)}

/* Card header */
.card-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f5f5f5;flex-wrap:wrap;gap:10px;cursor:pointer;user-select:none}
.order-id{font-size:15px;font-weight:800;color:#333}
.order-id span{color:#e91e63}
.order-meta{display:flex;gap:16px;flex-wrap:wrap}
.order-meta .meta{font-size:12px;color:#888;display:flex;align-items:center;gap:5px}
.order-meta .meta i{color:#e91e63}
.status-badge{display:flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:700}
.chevron{font-size:13px;color:#aaa;transition:transform .3s}
.card-head.open .chevron{transform:rotate(180deg)}

/* Progress bar */
.progress-wrap{padding:16px 20px 0;display:none}
.progress-wrap.open{display:block}
.progress-steps{display:flex;align-items:center;margin-bottom:16px}
.p-step{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1}
.p-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;border:2px solid #eee;background:#f9f9f9;color:#ccc}
.p-icon.done{background:#e91e63;border-color:#e91e63;color:#fff}
.p-icon.active{background:#fff;border-color:#e91e63;color:#e91e63}
.p-label{font-size:10px;color:#aaa;text-align:center;font-weight:600}
.p-label.done,.p-label.active{color:#e91e63}
.p-line{flex:1;height:2px;background:#eee;margin-bottom:22px}
.p-line.done{background:#e91e63}

/* Items table */
.items-wrap{padding:0 20px 16px;display:none}
.items-wrap.open{display:block}
.item-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f9f9f9}
.item-row:last-child{border-bottom:none}
.item-row img{width:54px;height:54px;object-fit:cover;border-radius:10px;border:1px solid #eee}
.item-info{flex:1}
.item-name{font-size:13px;font-weight:600;color:#333}
.item-qty{font-size:12px;color:#aaa;margin-top:2px}
.item-price{font-size:14px;font-weight:700;color:#222}

/* Footer row */
.card-foot{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fafafa;border-top:1px solid #f0f0f0;flex-wrap:wrap;gap:10px;display:none}
.card-foot.open{display:flex}
.total-label{font-size:13px;color:#666}
.total-amount{font-size:18px;font-weight:800;color:#e91e63}
.btn-cancel{padding:9px 20px;background:#fff;border:2px solid #e53935;color:#e53935;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;transition:background .2s,color .2s}
.btn-cancel:hover{background:#e53935;color:#fff}
.payment-chip{font-size:12px;background:#f3e5f5;color:#9c27b0;padding:4px 10px;border-radius:12px;font-weight:600}

/* Empty */
.empty{text-align:center;padding:60px 20px;background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,.07)}
.empty i{font-size:64px;color:#f3e5f5;margin-bottom:16px;display:block}
.empty p{color:#aaa;font-size:15px;margin-bottom:20px}
.btn-shop{display:inline-block;padding:12px 28px;background:linear-gradient(135deg,#e91e63,#9c27b0);color:#fff;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px}

@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.order-card{animation:fadeUp .4s ease both}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<div class="page-wrap">

  <div class="page-header">
    <h1>My <span>Orders</span></h1>
    <span class="order-count"><?= count($orders) ?> Order<?= count($orders) != 1 ? 's' : '' ?></span>
  </div>

  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($_GET['message']) ?></div>
  <?php endif; ?>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($_GET['error']) ?></div>
  <?php endif; ?>

  <?php if (empty($orders)): ?>
    <div class="empty">
      <i class="fa fa-box-open"></i>
      <p>You haven't placed any orders yet.</p>
      <a href="category.php" class="btn-shop"><i class="fa fa-shopping-bag"></i> Start Shopping</a>
    </div>
  <?php else: ?>

    <?php foreach ($orders as $idx => $order):
      $status = strtolower($order['order_status']);
      $cfg    = $statusConfig[$order['order_status']] ?? $statusConfig['pending'];

      // Fetch items
      $istmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?");
      $istmt->execute([$order['id']]);
      $items = $istmt->fetchAll(PDO::FETCH_ASSOC);

      // Progress step index
      $steps = ['pending','processing','shipped','completed'];
      $stepIdx = array_search($status, $steps);
      if ($status === 'paid') $stepIdx = 1;
      if ($stepIdx === false) $stepIdx = -1;

      $cardId = 'card_' . $order['id'];
    ?>
    <div class="order-card" style="animation-delay:<?= $idx * .07 ?>s">

      <!-- Header -->
      <div class="card-head" onclick="toggleCard('<?= $cardId ?>')">
        <div>
          <div class="order-id">Order <span>#<?= str_pad($order['id'],6,'0',STR_PAD_LEFT) ?></span></div>
          <div class="order-meta">
            <span class="meta"><i class="fa fa-calendar"></i><?= date('d M Y', strtotime($order['created_at'] ?? 'now')) ?></span>
            <span class="meta"><i class="fa fa-credit-card"></i><?= $order['payment_method'] === 'COD' ? 'Cash on Delivery' : 'Online' ?></span>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <span class="status-badge" style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['color'] ?>">
            <i class="fa <?= $cfg['icon'] ?>"></i><?= $cfg['label'] ?>
          </span>
          <i class="fa fa-chevron-down chevron" id="chev_<?= $cardId ?>"></i>
        </div>
      </div>

      <!-- Progress -->
      <div class="progress-wrap" id="prog_<?= $cardId ?>">
        <?php if ($status !== 'cancelled'): ?>
        <div class="progress-steps">
          <?php
          $pSteps = [
            ['fa-clipboard-check','Ordered'],
            ['fa-box','Packing'],
            ['fa-truck','Shipped'],
            ['fa-home','Delivered'],
          ];
          foreach ($pSteps as $pi => $ps):
            $cls = $pi < $stepIdx ? 'done' : ($pi === $stepIdx ? 'active' : '');
          ?>
            <div class="p-step">
              <div class="p-icon <?= $cls ?>"><i class="fa <?= $ps[0] ?>"></i></div>
              <div class="p-label <?= $cls ?>"><?= $ps[1] ?></div>
            </div>
            <?php if ($pi < count($pSteps)-1): ?>
              <div class="p-line <?= $pi < $stepIdx ? 'done' : '' ?>"></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Items -->
      <div class="items-wrap" id="items_<?= $cardId ?>">
        <?php foreach ($items as $item): ?>
        <div class="item-row">
          <img src="../image/<?= htmlspecialchars($item['image']) ?>" alt="">
          <div class="item-info">
            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
            <div class="item-qty">Qty: <?= $item['quantity'] ?></div>
          </div>
          <div class="item-price">₹<?= number_format($item['price'] * $item['quantity']) ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Footer -->
      <div class="card-foot" id="foot_<?= $cardId ?>">
        <div>
          <div class="total-label">Order Total</div>
          <div class="total-amount">₹<?= number_format($order['total_price']) ?></div>
        </div>
        <?php if ($status !== 'completed' && $status !== 'cancelled'): ?>
        <form action="cencel_order.php" method="POST">
          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
          <button type="submit" class="btn-cancel" onclick="return confirm('Cancel this order?')">
            <i class="fa fa-times"></i> Cancel Order
          </button>
        </form>
        <?php else: ?>
          <span class="payment-chip"><i class="fa fa-check"></i> <?= $cfg['label'] ?></span>
        <?php endif; ?>
      </div>

    </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

<?php include "../includes/footer.php"; ?>

<script>
function toggleCard(id) {
  const head  = document.querySelector(`[onclick="toggleCard('${id}')"]`);
  const prog  = document.getElementById('prog_'  + id);
  const items = document.getElementById('items_' + id);
  const foot  = document.getElementById('foot_'  + id);
  const chev  = document.getElementById('chev_'  + id);

  const isOpen = prog.classList.contains('open');
  prog.classList.toggle('open', !isOpen);
  items.classList.toggle('open', !isOpen);
  foot.classList.toggle('open', !isOpen);
  head.classList.toggle('open', !isOpen);
}

// Auto-open first order
document.addEventListener('DOMContentLoaded', () => {
  const first = document.querySelector('.card-head');
  if (first) first.click();
});
</script>
</body>
</html>
