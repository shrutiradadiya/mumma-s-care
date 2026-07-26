<?php
require_once '../includes/connect.php';
session_start();

$category    = isset($_GET['category']) ? $_GET['category'] : 'all';
$searchQuery = isset($_GET['search'])   ? $_GET['search']   : '';

$query = "SELECT * FROM products WHERE 1";
if ($category && $category != 'all') $query .= " AND category LIKE ?";
if ($searchQuery)                     $query .= " AND name LIKE ?";

$stmt = $conn->prepare($query);
if ($stmt === false) die('Query error: ' . $conn->error);

$categoryTerm = "%" . $category . "%";
$searchTerm   = "%" . $searchQuery . "%";

if ($category != 'all' && $searchQuery)  $stmt->bind_param("ss", $categoryTerm, $searchTerm);
elseif ($category != 'all')              $stmt->bind_param("s",  $categoryTerm);
elseif ($searchQuery)                    $stmt->bind_param("s",  $searchTerm);

$stmt->execute();
$result   = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) $products[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($category == 'all' ? 'All Products' : ucfirst($category)) ?> - Mumma's Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#f5f5f5;color:#333}

/* ── Toolbar ── */
.toolbar{display:flex;align-items:center;justify-content:space-between;padding:12px 20px;background:#fff;border-bottom:1px solid #e0e0e0;flex-wrap:wrap;gap:10px}
.toolbar .result-count{font-size:14px;color:#666}
.sort-select{padding:7px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;cursor:pointer;background:#fff}

/* ── Grid ── */
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;padding:20px}

/* ── Card ── */
.product-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.1);transition:box-shadow .25s,transform .25s;position:relative;cursor:pointer}
.product-card:hover{box-shadow:0 6px 20px rgba(0,0,0,.15);transform:translateY(-3px)}

/* Image flip area */
.img-wrap{position:relative;width:100%;padding-top:110%;overflow:hidden;background:#f9f9f9}
.img-wrap img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:opacity .4s ease}
.img-wrap .img-back{opacity:0}
.product-card:hover .img-front{opacity:0}
.product-card:hover .img-back{opacity:1}

/* Badges */
.badge-wrap{position:absolute;top:8px;left:8px;display:flex;flex-direction:column;gap:4px;z-index:2}
.badge{font-size:11px;font-weight:700;padding:3px 7px;border-radius:4px;color:#fff}
.badge-new{background:#ff6161}
.badge-sale{background:#ff9800}

/* Wishlist heart */
.wish-btn{position:absolute;top:8px;right:8px;z-index:2;background:#fff;border:none;border-radius:50%;width:34px;height:34px;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(0,0,0,.2);cursor:pointer;transition:transform .2s}
.wish-btn:hover{transform:scale(1.15)}
.wish-btn i{font-size:16px;color:#ccc;transition:color .2s}
.wish-btn.active i{color:#e91e63}

/* Quick-view button */
.quick-view-btn{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.65);color:#fff;border:none;padding:9px;font-size:13px;opacity:0;transition:opacity .25s;cursor:pointer;letter-spacing:.5px}
.product-card:hover .quick-view-btn{opacity:1}

/* Card body */
.card-body{padding:10px 12px 14px}
.card-brand{font-size:11px;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px}
.card-name{font-size:14px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:4px}
.card-desc{font-size:12px;color:#999;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:6px}

/* Price row */
.price-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.price-now{font-size:17px;font-weight:700;color:#222}
.price-old{font-size:13px;color:#aaa;text-decoration:line-through}
.price-off{font-size:12px;color:#388e3c;font-weight:600}

/* Action buttons */
.card-actions{display:flex;gap:8px;padding:0 12px 12px}
.btn-cart,.btn-buy{flex:1;padding:9px 6px;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s,transform .1s}
.btn-cart{background:#fff3e0;color:#e65100;border:1px solid #ffcc80}
.btn-cart:hover{background:#ffe0b2}
.btn-buy{background:#ff6161;color:#fff}
.btn-buy:hover{background:#e53935}
.btn-cart:active,.btn-buy:active{transform:scale(.97)}

/* ── Quick-view Modal ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:1000;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:14px;max-width:860px;width:95%;max-height:90vh;overflow-y:auto;display:flex;flex-wrap:wrap;position:relative}
.modal-close{position:absolute;top:12px;right:14px;background:none;border:none;font-size:22px;cursor:pointer;color:#555;z-index:10}

/* Modal gallery */
.modal-gallery{flex:0 0 45%;padding:20px;display:flex;flex-direction:column;gap:10px}
.modal-main-img{width:100%;border-radius:10px;object-fit:cover;max-height:320px}
.modal-thumbs{display:flex;gap:8px;flex-wrap:wrap}
.modal-thumbs img{width:60px;height:60px;object-fit:cover;border-radius:6px;border:2px solid transparent;cursor:pointer;transition:border-color .2s}
.modal-thumbs img.active{border-color:#ff6161}

/* Modal info */
.modal-info{flex:1;padding:24px 20px 20px}
.modal-info h2{font-size:20px;margin-bottom:6px}
.modal-info .modal-price{font-size:24px;font-weight:700;color:#222;margin:8px 0}
.modal-info .modal-old{font-size:14px;color:#aaa;text-decoration:line-through;margin-right:8px}
.modal-info .modal-off{font-size:13px;color:#388e3c;font-weight:600}
.modal-info p{font-size:14px;color:#555;line-height:1.6;margin:10px 0}
.modal-info .modal-actions{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap}
.modal-info .modal-actions .btn-cart,.modal-info .modal-actions .btn-buy{padding:11px 20px;font-size:14px;border-radius:8px}

/* Empty state */
.empty{text-align:center;padding:60px 20px;color:#aaa}
.empty i{font-size:60px;margin-bottom:16px;display:block}

/* Responsive */
@media(max-width:600px){
  .product-grid{grid-template-columns:repeat(2,1fr);gap:10px;padding:10px}
  .modal{flex-direction:column}
  .modal-gallery{flex:none;width:100%}
}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<!-- Toolbar -->
<div class="toolbar">
  <span class="result-count"><?= count($products) ?> products found<?= $category != 'all' ? ' in <strong>'.htmlspecialchars(ucfirst($category)).'</strong>' : '' ?></span>
  <select class="sort-select" id="sortSelect">
    <option value="default">Sort: Relevance</option>
    <option value="price-asc">Price: Low to High</option>
    <option value="price-desc">Price: High to Low</option>
    <option value="name-asc">Name: A–Z</option>
  </select>
</div>

<!-- Product Grid -->
<div class="product-grid" id="productGrid">
<?php if (empty($products)): ?>
  <div class="empty" style="grid-column:1/-1">
    <i class="fa fa-box-open"></i>
    <p>No products found.</p>
  </div>
<?php else: ?>
  <?php foreach ($products as $i => $p):
    // Simulate a "back" image by using next image number in same folder
    $imgPath  = $p['image'];                          // e.g. cloth/1.jpg  OR  uploads/myimg.jpg
    // Admin-uploaded images are stored as uploads/xxx — serve from ../image/uploads/
    // Legacy images are stored as cloth/1.jpg etc — serve from ../image/
    $imgFront = '../image/' . $imgPath;
    // Try to derive a back image (increment number in filename)
    preg_match('/^(.*?)(\d+)(\.\w+)$/', $imgPath, $m);
    $backNum  = isset($m[2]) ? ($m[2] % 19) + 1 : 1; // cycle 1-19
    // For admin-uploaded images, use same image for front & back
    if (strpos($imgPath, 'uploads/') === 0) {
        $imgBack = $imgFront;
    } else {
        $imgBack = isset($m[1]) ? '../image/' . $m[1] . $backNum . $m[3] : $imgFront;
    }

    $discount = rand(10, 40);
    $oldPrice = round($p['price'] * 100 / (100 - $discount));
    $isNew    = ($i % 5 === 0);
  ?>
  <div class="product-card"
       data-id="<?= $p['id'] ?>"
       data-name="<?= htmlspecialchars($p['name']) ?>"
       data-price="<?= $p['price'] ?>"
       data-old="<?= $oldPrice ?>"
       data-off="<?= $discount ?>"
       data-desc="<?= htmlspecialchars($p['description']) ?>"
       data-img="<?= htmlspecialchars($imgFront) ?>"
       data-img2="<?= htmlspecialchars($imgBack) ?>">

    <div class="img-wrap">
      <img class="img-front" src="<?= htmlspecialchars($imgFront) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
      <img class="img-back"  src="<?= htmlspecialchars($imgBack)  ?>" alt="<?= htmlspecialchars($p['name']) ?> back" loading="lazy">

      <div class="badge-wrap">
        <?php if ($isNew): ?><span class="badge badge-new">NEW</span><?php endif; ?>
        <span class="badge badge-sale"><?= $discount ?>% OFF</span>
      </div>

      <button class="wish-btn" onclick="toggleWish(this,<?= $p['id'] ?>)" title="Wishlist">
        <i class="fa-heart <?= isset($_SESSION['wishlist']) && in_array($p['id'], $_SESSION['wishlist']) ? 'fas' : 'far' ?>"></i>
      </button>

      <button class="quick-view-btn" onclick="openModal(this.closest('.product-card'))">
        <i class="fa fa-eye"></i> Quick View
      </button>
    </div>

    <div class="card-body">
      <div class="card-brand">Mumma's Care</div>
      <div class="card-name"><?= htmlspecialchars($p['name']) ?></div>
      <div class="card-desc"><?= htmlspecialchars($p['description']) ?></div>
      <div class="price-row">
        <span class="price-now">₹<?= number_format($p['price']) ?></span>
        <span class="price-old">₹<?= number_format($oldPrice) ?></span>
        <span class="price-off"><?= $discount ?>% off</span>
      </div>
    </div>

    <div class="card-actions">
      <form method="POST" action="add_to_cart.php" style="flex:1;display:flex">
        <input type="hidden" name="add_to_cart" value="<?= $p['id'] ?>">
        <button type="submit" class="btn-cart" style="width:100%"><i class="fa fa-cart-plus"></i> Cart</button>
      </form>
      <form method="POST" action="add_to_cart.php?buy_now=1" style="flex:1;display:flex">
        <input type="hidden" name="add_to_cart" value="<?= $p['id'] ?>">
        <button type="submit" class="btn-buy" style="width:100%"><i class="fa fa-bolt"></i> Buy</button>
      </form>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>
</div>

<!-- Quick-view Modal -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
  <div class="modal" id="modal">
    <button class="modal-close" onclick="closeModal()"><i class="fa fa-times"></i></button>

    <div class="modal-gallery">
      <img class="modal-main-img" id="modalMainImg" src="" alt="">
      <div class="modal-thumbs" id="modalThumbs"></div>
    </div>

    <div class="modal-info">
      <div class="card-brand">Mumma's Care</div>
      <h2 id="modalName"></h2>
      <div class="modal-price">
        <span class="modal-old" id="modalOld"></span>
        <span id="modalPrice"></span>
        <span class="modal-off" id="modalOff"></span>
      </div>
      <p id="modalDesc"></p>
      <div class="modal-actions">
        <form method="POST" action="add_to_cart.php" style="display:contents">
          <input type="hidden" name="add_to_cart" id="modalCartId">
          <button type="submit" class="btn-cart"><i class="fa fa-cart-plus"></i> Add to Cart</button>
        </form>
        <form method="POST" action="add_to_cart.php?buy_now=1" style="display:contents">
          <input type="hidden" name="add_to_cart" id="modalBuyId">
          <button type="submit" class="btn-buy"><i class="fa fa-bolt"></i> Buy Now</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "../includes/footer.php"; ?>

<script>
// ── Sort ──
document.getElementById('sortSelect').addEventListener('change', function(){
  const grid  = document.getElementById('productGrid');
  const cards = [...grid.querySelectorAll('.product-card')];
  const val   = this.value;
  cards.sort((a,b)=>{
    if(val==='price-asc')  return +a.dataset.price - +b.dataset.price;
    if(val==='price-desc') return +b.dataset.price - +a.dataset.price;
    if(val==='name-asc')   return a.dataset.name.localeCompare(b.dataset.name);
    return 0;
  });
  cards.forEach(c=>grid.appendChild(c));
});

// ── Wishlist toggle ──
function toggleWish(btn, id){
  event.stopPropagation();
  event.preventDefault();
  btn.classList.toggle('active');
  const icon = btn.querySelector('i');
  icon.classList.toggle('fas');
  icon.classList.toggle('far');
  fetch('wishlist.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body: btn.classList.contains('active') ? 'add_to_wishlist='+id : 'remove_from_wishlist='+id
  });
}

// ── Quick-view Modal ──
function openModal(card){
  const d = card.dataset;
  document.getElementById('modalName').textContent  = d.name;
  document.getElementById('modalPrice').textContent = '₹' + Number(d.price).toLocaleString('en-IN');
  document.getElementById('modalOld').textContent   = '₹' + Number(d.old).toLocaleString('en-IN');
  document.getElementById('modalOff').textContent   = d.off + '% off';
  document.getElementById('modalDesc').textContent  = d.desc;
  document.getElementById('modalCartId').value      = d.id;
  document.getElementById('modalBuyId').value       = d.id;

  // Thumbnails
  const thumbs = [d.img, d.img2];
  const thumbsEl = document.getElementById('modalThumbs');
  thumbsEl.innerHTML = '';
  thumbs.forEach((src,i)=>{
    const img = document.createElement('img');
    img.src = src; img.alt = d.name + ' view ' + (i+1);
    if(i===0) img.classList.add('active');
    img.onclick = ()=>{ setMainImg(src); thumbsEl.querySelectorAll('img').forEach(t=>t.classList.remove('active')); img.classList.add('active'); };
    thumbsEl.appendChild(img);
  });

  setMainImg(d.img);
  document.getElementById('modalOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function setMainImg(src){
  document.getElementById('modalMainImg').src = src;
}

function closeModal(){
  document.getElementById('modalOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

function closeModalOutside(e){
  if(e.target === document.getElementById('modalOverlay')) closeModal();
}

document.addEventListener('keydown', e=>{ if(e.key==='Escape') closeModal(); });
</script>
</body>
</html>
