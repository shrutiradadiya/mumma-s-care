<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mumma's Care - Baby Products</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#fff;color:#333;overflow-x:hidden}

/* ── HERO SLIDER ── */
.slider{position:relative;width:100%;height:88vh;min-height:320px;overflow:hidden}
.slide{position:absolute;inset:0;opacity:0;transition:opacity .8s ease}
.slide.active{opacity:1;z-index:1}
.slide img{width:100%;height:100%;object-fit:cover}
.slide-overlay{position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.55) 40%,transparent)}
.slide-content{position:absolute;top:50%;left:7%;transform:translateY(-50%);color:#fff;z-index:2;max-width:520px}
.slide-content .tag{display:inline-block;background:#e91e63;color:#fff;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:12px;letter-spacing:1px;text-transform:uppercase}
.slide-content h1{font-size:clamp(26px,5vw,52px);font-weight:900;line-height:1.15;margin-bottom:14px}
.slide-content p{font-size:clamp(13px,2vw,17px);opacity:.9;margin-bottom:22px;line-height:1.6}
.slide-content a{display:inline-block;padding:13px 30px;background:#e91e63;color:#fff;border-radius:30px;font-weight:700;text-decoration:none;font-size:15px;transition:background .2s,transform .2s;box-shadow:0 4px 16px rgba(233,30,99,.4)}
.slide-content a:hover{background:#c2185b;transform:translateY(-2px)}

.slider-btn{position:absolute;top:50%;transform:translateY(-50%);z-index:10;background:rgba(255,255,255,.2);backdrop-filter:blur(4px);border:none;color:#fff;width:44px;height:44px;border-radius:50%;font-size:16px;cursor:pointer;transition:background .2s}
.slider-btn:hover{background:rgba(255,255,255,.4)}
.slider-btn.prev{left:16px}
.slider-btn.next{right:16px}
.dots{position:absolute;bottom:18px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:10}
.dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.5);cursor:pointer;transition:background .2s,transform .2s}
.dot.active{background:#fff;transform:scale(1.3)}

/* ── OFFER STRIP ── */
.offer-strip{background:linear-gradient(135deg,#e91e63,#9c27b0);color:#fff;text-align:center;padding:12px;font-size:14px;font-weight:600;letter-spacing:.5px}
.offer-strip span{margin:0 20px}

/* ── SECTION COMMON ── */
.section{padding:56px 20px;max-width:1200px;margin:0 auto}
.section-title{text-align:center;margin-bottom:36px}
.section-title h2{font-size:clamp(22px,4vw,34px);font-weight:800;color:#222}
.section-title h2 span{color:#e91e63}
.section-title p{color:#888;font-size:15px;margin-top:6px}

/* ── CATEGORIES ── */
.cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px}
.cat-card{border-radius:16px;overflow:hidden;position:relative;cursor:pointer;box-shadow:0 2px 10px rgba(0,0,0,.08);transition:transform .25s,box-shadow .25s;text-decoration:none}
.cat-card:hover{transform:translateY(-5px);box-shadow:0 8px 24px rgba(0,0,0,.14)}
.cat-card img{width:100%;height:160px;object-fit:cover;display:block;transition:transform .4s}
.cat-card:hover img{transform:scale(1.07)}
.cat-card .cat-label{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.7));color:#fff;padding:20px 10px 10px;font-size:13px;font-weight:700;text-align:center}

/* ── FEATURES ── */
.features-bg{background:#fdf2f8;padding:40px 20px}
.feat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;max-width:1100px;margin:0 auto}
.feat-card{background:#fff;border-radius:14px;padding:24px 18px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.feat-icon{width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:22px}
.feat-card h4{font-size:14px;font-weight:700;margin-bottom:6px;color:#333}
.feat-card p{font-size:12px;color:#999;line-height:1.6}

/* ── BANNER ── */
.banner-wrap{display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:0 20px;max-width:1200px;margin:0 auto 40px}
.banner-card{border-radius:16px;overflow:hidden;position:relative;height:220px}
.banner-card img{width:100%;height:100%;object-fit:cover}
.banner-card .b-overlay{position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.5),transparent);display:flex;flex-direction:column;justify-content:center;padding:24px}
.banner-card .b-overlay h3{color:#fff;font-size:clamp(16px,2.5vw,24px);font-weight:800;margin-bottom:8px}
.banner-card .b-overlay a{display:inline-block;padding:8px 20px;background:#e91e63;color:#fff;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none}

/* ── TIPS ── */
.tips-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px}
.tip-card{border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.07)}
.tip-card img{width:100%;height:160px;object-fit:cover}
.tip-body{padding:16px;background:#fff}
.tip-body .tip-tag{font-size:11px;font-weight:700;color:#e91e63;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
.tip-body h4{font-size:15px;font-weight:700;color:#333;margin-bottom:6px}
.tip-body p{font-size:13px;color:#888;line-height:1.6}

/* ── TESTIMONIALS ── */
.testi-bg{background:linear-gradient(135deg,#fce4ec,#f3e5f5);padding:56px 20px}
.testi-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;max-width:1100px;margin:0 auto}
.testi-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.07);position:relative}
.testi-card .quote{font-size:40px;color:#f8bbd0;line-height:1;margin-bottom:8px}
.testi-card p{font-size:14px;color:#555;line-height:1.7;margin-bottom:14px}
.testi-card .author{display:flex;align-items:center;gap:10px}
.testi-card .avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#e91e63,#9c27b0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px}
.testi-card .author-info strong{font-size:14px;color:#333;display:block}
.testi-card .author-info span{font-size:12px;color:#aaa}
.stars-row{color:#f5a623;font-size:13px;margin-bottom:10px}

/* ── CTA ── */
.cta-section{background:linear-gradient(135deg,#e91e63,#9c27b0);padding:60px 20px;text-align:center;color:#fff}
.cta-section h2{font-size:clamp(22px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-section p{font-size:15px;opacity:.9;margin-bottom:24px}
.cta-section a{display:inline-block;padding:14px 36px;background:#fff;color:#e91e63;border-radius:30px;font-weight:800;font-size:16px;text-decoration:none;transition:transform .2s}
.cta-section a:hover{transform:scale(1.04)}

@media(max-width:600px){
  .banner-wrap{grid-template-columns:1fr}
  .slider{height:60vh}
}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<!-- ── OFFER STRIP ── -->
<div class="offer-strip">
  <span><i class="fa fa-truck"></i> Free Delivery on orders above ₹499</span>
  <span><i class="fa fa-tag"></i> Use code MUMMA10 for 10% off</span>
  <span><i class="fa fa-shield-alt"></i> 100% Safe & Trusted Products</span>
</div>

<!-- ── HERO SLIDER ── -->
<div class="slider" id="slider">
  <div class="slide active">
    <img src="../image offer/bg.jpg" alt="Welcome">
    <div class="slide-overlay"></div>
    <div class="slide-content">
      <span class="tag">New Arrivals</span>
      <h1>Everything Your Baby Needs</h1>
      <p>Premium quality baby products — safe, gentle, and loved by thousands of parents.</p>
      <a href="category.php">Shop Now <i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="slide">
    <img src="../image offer/dipers.jpeg" alt="Diapers">
    <div class="slide-overlay"></div>
    <div class="slide-content">
      <span class="tag">Best Seller</span>
      <h1>Soft & Dry Diapers</h1>
      <p>Keep your baby comfortable all day and night with our premium diaper range.</p>
      <a href="category.php?category=Diapers">Explore Diapers</a>
    </div>
  </div>
  <div class="slide">
    <img src="../image offer/skincare.jpg" alt="Skin Care">
    <div class="slide-overlay"></div>
    <div class="slide-content">
      <span class="tag">Gentle Formula</span>
      <h1>Baby Skin Care Range</h1>
      <p>Dermatologist-tested products made for your baby's delicate skin.</p>
      <a href="category.php?category=Skin Care">Shop Skin Care</a>
    </div>
  </div>
  <div class="slide">
    <img src="../image offer/toys.jpeg" alt="Toys">
    <div class="slide-overlay"></div>
    <div class="slide-content">
      <span class="tag">Fun & Learning</span>
      <h1>Toys That Inspire</h1>
      <p>Educational and fun toys designed to spark creativity and joy.</p>
      <a href="category.php?category=Toys">View Toys</a>
    </div>
  </div>
  <div class="slide">
    <img src="../image offer/offer.jpeg" alt="Offers">
    <div class="slide-overlay"></div>
    <div class="slide-content">
      <span class="tag">Limited Time</span>
      <h1>Big Savings Today!</h1>
      <p>Grab the best deals on all baby essentials before they're gone.</p>
      <a href="category.php">See All Offers</a>
    </div>
  </div>

  <button class="slider-btn prev" id="prevBtn"><i class="fa fa-chevron-left"></i></button>
  <button class="slider-btn next" id="nextBtn"><i class="fa fa-chevron-right"></i></button>
  <div class="dots" id="dots"></div>
</div>

<!-- ── CATEGORIES ── -->
<div class="section">
  <div class="section-title">
    <h2>Shop by <span>Category</span></h2>
    <p>Find everything your little one needs</p>
  </div>
  <div class="cat-grid">
    <?php
    $cats = [
      ['Diapers',           'dipers.jpeg'],
      ['Skin Care',         'skincare.jpg'],
      ['Toys',              'toys.jpeg'],
      ['Feeding Accessories','fedding.jpg'],
      ['Baby Gear',         'gear.jpg'],
      ['Celebration Kit',   'celebreat.webp'],
      ['Girl\'s Fashion',   'fashion.webp'],
      ['Boy\'s Fashion',    'fashion.webp'],
    ];
    foreach($cats as $c):
    ?>
    <a class="cat-card" href="category.php?category=<?= urlencode($c[0]) ?>">
      <img src="../image offer/<?= $c[1] ?>" alt="<?= $c[0] ?>">
      <div class="cat-label"><?= $c[0] ?></div>
    </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── FEATURES ── -->
<div class="features-bg">
  <div class="section-title">
    <h2>Why <span>Mumma's Care?</span></h2>
  </div>
  <div class="feat-grid">
    <div class="feat-card">
      <div class="feat-icon" style="background:#fce4ec"><i class="fa fa-truck" style="color:#e91e63"></i></div>
      <h4>Fast Delivery</h4>
      <p>Get your orders delivered quickly right to your doorstep.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:#e8f5e9"><i class="fa fa-leaf" style="color:#4caf50"></i></div>
      <h4>100% Safe Products</h4>
      <p>All products are tested and certified safe for babies.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:#e3f2fd"><i class="fa fa-undo" style="color:#2196f3"></i></div>
      <h4>Easy Returns</h4>
      <p>Hassle-free returns within 7 days of delivery.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:#fff3e0"><i class="fa fa-headset" style="color:#ff9800"></i></div>
      <h4>24/7 Support</h4>
      <p>Our team is always here to help you with any queries.</p>
    </div>
    <div class="feat-card">
      <div class="feat-icon" style="background:#f3e5f5"><i class="fa fa-lock" style="color:#9c27b0"></i></div>
      <h4>Secure Payments</h4>
      <p>Your payment information is always safe with us.</p>
    </div>
  </div>
</div>

<!-- ── PROMO BANNERS ── -->
<div style="padding:40px 0 0">
  <div class="banner-wrap">
    <div class="banner-card">
      <img src="../image offer/fashion.webp" alt="Fashion">
      <div class="b-overlay">
        <h3>Baby Fashion Collection</h3>
        <a href="category.php?category=Girl's Fashion">Shop Now</a>
      </div>
    </div>
    <div class="banner-card">
      <img src="../image offer/gear.jpg" alt="Baby Gear">
      <div class="b-overlay">
        <h3>Essential Baby Gear</h3>
        <a href="category.php?category=Baby Gear">Explore</a>
      </div>
    </div>
  </div>
  <div class="banner-wrap">
    <div class="banner-card">
      <img src="../image offer/celebreat.webp" alt="Celebration">
      <div class="b-overlay">
        <h3>Celebration Kits</h3>
        <a href="category.php?category=Celebration Kit">View Kits</a>
      </div>
    </div>
    <div class="banner-card">
      <img src="../image offer/fedding.jpg" alt="Feeding">
      <div class="b-overlay">
        <h3>Feeding Accessories</h3>
        <a href="category.php?category=Feeding Accessories">Shop Now</a>
      </div>
    </div>
  </div>
</div>

<!-- ── BABY CARE TIPS ── -->
<div class="section">
  <div class="section-title">
    <h2>Baby Care <span>Tips</span></h2>
    <p>Expert advice for happy, healthy babies</p>
  </div>
  <div class="tips-grid">
    <div class="tip-card">
      <img src="../image offer/dipers.jpeg" alt="Diapering">
      <div class="tip-body">
        <div class="tip-tag">Diapering</div>
        <h4>Proper Diapering Guide</h4>
        <p>Learn how to keep your baby dry and rash-free all day long with the right technique.</p>
      </div>
    </div>
    <div class="tip-card">
      <img src="../image offer/skincare.jpg" alt="Skin Care">
      <div class="tip-body">
        <div class="tip-tag">Skin Care</div>
        <h4>Protecting Sensitive Skin</h4>
        <p>Tips for shielding your baby's delicate skin from dryness, rashes, and irritation.</p>
      </div>
    </div>
    <div class="tip-card">
      <img src="../image offer/fedding.jpg" alt="Nutrition">
      <div class="tip-body">
        <div class="tip-tag">Nutrition</div>
        <h4>Feeding & Nutrition</h4>
        <p>Simple steps to ensure your baby gets the right nutrients at every stage.</p>
      </div>
    </div>
    <div class="tip-card">
      <img src="../image offer/happy beby.jpg" alt="Happy Baby">
      <div class="tip-body">
        <div class="tip-tag">Wellness</div>
        <h4>Keeping Baby Happy</h4>
        <p>Daily routines and activities that promote your baby's emotional well-being.</p>
      </div>
    </div>
  </div>
</div>

<!-- ── TESTIMONIALS ── -->
<div class="testi-bg">
  <div class="section-title">
    <h2>What <span>Parents Say</span></h2>
    <p>Trusted by thousands of happy families</p>
  </div>
  <div class="testi-grid">
    <?php
    $testimonials = [
      ['Priya S.',  'P', "Mumma's Care has the best diapers! My baby stays dry and comfortable all night. Absolutely love the quality.",        'New Mom, Mumbai'],
      ['Rohan K.',  'R', "Fast delivery and amazing products. The baby gear is sturdy and well-made. Highly recommend to all new parents.",    'Dad of Twins, Delhi'],
      ['Anjali M.', 'A', "The baby skincare range is so gentle on my little one's skin. No rashes, no irritation. We are loyal customers now.", 'Mom, Bangalore'],
      ['Sneha T.',  'S', "Ordered the celebration kit for my baby's first birthday — it was perfect! Great packaging and quick delivery.",       'Mom, Pune'],
    ];
    foreach($testimonials as $t): ?>
    <div class="testi-card">
      <div class="quote">"</div>
      <div class="stars-row">
        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
      </div>
      <p><?= $t[2] ?></p>
      <div class="author">
        <div class="avatar"><?= $t[1] ?></div>
        <div class="author-info">
          <strong><?= $t[0] ?></strong>
          <span><?= $t[3] ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── CTA ── -->
<div class="cta-section">
  <h2>Ready to Shop for Your Little One?</h2>
  <p>Explore hundreds of safe, quality baby products — all in one place.</p>
  <a href="category.php"><i class="fa fa-shopping-bag"></i> Start Shopping</a>
</div>

<?php include "../includes/footer.php"; ?>

<script>
const slides = document.querySelectorAll('.slide');
const dotsWrap = document.getElementById('dots');
let cur = 0, timer;

// Build dots
slides.forEach((_,i) => {
  const d = document.createElement('div');
  d.className = 'dot' + (i===0?' active':'');
  d.onclick = () => goTo(i);
  dotsWrap.appendChild(d);
});

function goTo(n) {
  slides[cur].classList.remove('active');
  dotsWrap.children[cur].classList.remove('active');
  cur = (n + slides.length) % slides.length;
  slides[cur].classList.add('active');
  dotsWrap.children[cur].classList.add('active');
  resetTimer();
}

function resetTimer() {
  clearInterval(timer);
  timer = setInterval(() => goTo(cur + 1), 4000);
}

document.getElementById('nextBtn').onclick = () => goTo(cur + 1);
document.getElementById('prevBtn').onclick = () => goTo(cur - 1);
resetTimer();
</script>
</body>
</html>
