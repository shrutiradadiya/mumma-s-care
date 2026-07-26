<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Mumma's Care</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Arial,sans-serif;background:#fff;color:#333;overflow-x:hidden}

/* ── HERO ── */
.hero{position:relative;height:420px;overflow:hidden}
.hero img{width:100%;height:100%;object-fit:cover;object-position:center 30%}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(233,30,99,.75),rgba(156,39,176,.6))}
.hero-content{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:#fff;padding:20px}
.hero-content h1{font-size:clamp(28px,5vw,52px);font-weight:900;margin-bottom:12px}
.hero-content p{font-size:clamp(14px,2vw,18px);opacity:.92;max-width:560px;line-height:1.7}
.breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px;opacity:.8;margin-top:14px}
.breadcrumb a{color:#fff;text-decoration:none}
.breadcrumb i{font-size:10px}

/* ── SECTION ── */
.section{max-width:1100px;margin:0 auto;padding:60px 20px}
.section-title{text-align:center;margin-bottom:40px}
.section-title h2{font-size:clamp(22px,4vw,34px);font-weight:800;color:#222}
.section-title h2 span{color:#e91e63}
.section-title p{color:#888;font-size:15px;margin-top:8px}

/* ── STORY ── */
.story-wrap{display:flex;align-items:center;gap:48px;flex-wrap:wrap}
.story-img{flex:1;min-width:260px;border-radius:20px;overflow:hidden;box-shadow:0 8px 32px rgba(233,30,99,.15)}
.story-img img{width:100%;height:360px;object-fit:cover;display:block}
.story-text{flex:1.2;min-width:260px}
.story-text .tag{display:inline-block;background:#fce4ec;color:#e91e63;font-size:12px;font-weight:700;padding:4px 14px;border-radius:20px;margin-bottom:14px;text-transform:uppercase;letter-spacing:.5px}
.story-text h2{font-size:clamp(22px,3.5vw,32px);font-weight:800;color:#222;margin-bottom:14px;line-height:1.3}
.story-text p{font-size:15px;color:#666;line-height:1.8;margin-bottom:14px}
.story-text .highlight{display:flex;gap:24px;margin-top:20px;flex-wrap:wrap}
.highlight-item{text-align:center}
.highlight-item .num{font-size:28px;font-weight:900;color:#e91e63}
.highlight-item .lbl{font-size:12px;color:#888;font-weight:600}

/* ── MISSION / VISION ── */
.mv-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px}
.mv-card{border-radius:16px;padding:28px 22px;text-align:center;transition:transform .25s,box-shadow .25s}
.mv-card:hover{transform:translateY(-5px);box-shadow:0 10px 28px rgba(0,0,0,.1)}
.mv-icon{width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:26px}
.mv-card h3{font-size:17px;font-weight:800;margin-bottom:8px;color:#222}
.mv-card p{font-size:14px;color:#777;line-height:1.7}

/* ── TEAM ── */
.team-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:24px}
.team-card{text-align:center}
.team-avatar{width:100px;height:100px;border-radius:50%;margin:0 auto 14px;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:#fff}
.team-card h4{font-size:15px;font-weight:700;color:#333;margin-bottom:4px}
.team-card span{font-size:13px;color:#e91e63;font-weight:600}
.team-card p{font-size:13px;color:#999;margin-top:6px;line-height:1.6}

/* ── REVIEWS ── */
.reviews-bg{background:#fdf2f8;padding:60px 20px}
.reviews-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;max-width:1100px;margin:0 auto}
.review-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.07)}
.review-card .stars{color:#f5a623;font-size:14px;margin-bottom:10px}
.review-card p{font-size:14px;color:#555;line-height:1.7;margin-bottom:16px}
.reviewer{display:flex;align-items:center;gap:12px}
.reviewer .ava{width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid #f8bbd0}
.reviewer .ava-init{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#e91e63,#9c27b0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px}
.reviewer-info strong{font-size:14px;color:#333;display:block}
.reviewer-info span{font-size:12px;color:#aaa}

/* ── VALUES ── */
.values-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px}
.val-card{display:flex;align-items:flex-start;gap:14px;background:#fff;border-radius:14px;padding:18px;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.val-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.val-card h4{font-size:14px;font-weight:700;color:#333;margin-bottom:4px}
.val-card p{font-size:12px;color:#999;line-height:1.6}

/* ── CTA ── */
.cta{background:linear-gradient(135deg,#e91e63,#9c27b0);padding:60px 20px;text-align:center;color:#fff}
.cta h2{font-size:clamp(22px,4vw,34px);font-weight:900;margin-bottom:10px}
.cta p{font-size:15px;opacity:.9;margin-bottom:24px}
.cta a{display:inline-block;padding:13px 34px;background:#fff;color:#e91e63;border-radius:30px;font-weight:800;font-size:15px;text-decoration:none;transition:transform .2s}
.cta a:hover{transform:scale(1.04)}

@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
.fade-up{animation:fadeUp .6s ease both}

@media(max-width:600px){
  .story-wrap{flex-direction:column}
  .hero{height:300px}
}
</style>
</head>
<body>
<?php include "../includes/header.php"; ?>

<!-- ── HERO ── -->
<div class="hero">
  <img src="../image offer/about.webp" alt="About Mumma's Care">
  <div class="hero-overlay"></div>
  <div class="hero-content fade-up">
    <h1>About Mumma's Care</h1>
    <p>We are on a mission to make parenting easier, safer, and more joyful — one product at a time.</p>
    <div class="breadcrumb">
      <a href="home.php">Home</a>
      <i class="fa fa-chevron-right"></i>
      <span>About Us</span>
    </div>
  </div>
</div>

<!-- ── OUR STORY ── -->
<div class="section">
  <div class="story-wrap">
    <div class="story-img">
      <img src="../image offer/happy beby.jpg" alt="Happy Baby">
    </div>
    <div class="story-text">
      <span class="tag">Our Story</span>
      <h2>Born from a Mother's Love</h2>
      <p>Mumma's Care was founded by a group of parents who understood the challenges of finding safe, affordable, and high-quality baby products. We started with a simple belief — every baby deserves the best, and every parent deserves peace of mind.</p>
      <p>From our humble beginnings, we have grown into a trusted destination for thousands of families across India, offering everything from diapers and skincare to toys and celebration kits.</p>
      <div class="highlight">
        <div class="highlight-item">
          <div class="num">10K+</div>
          <div class="lbl">Happy Families</div>
        </div>
        <div class="highlight-item">
          <div class="num">500+</div>
          <div class="lbl">Products</div>
        </div>
        <div class="highlight-item">
          <div class="num">4.8★</div>
          <div class="lbl">Avg Rating</div>
        </div>
        <div class="highlight-item">
          <div class="num">50+</div>
          <div class="lbl">Cities Served</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ── MISSION / VISION / VALUES ── -->
<div style="background:#fdf2f8;padding:60px 20px">
  <div class="section-title">
    <h2>What <span>Drives Us</span></h2>
    <p>Our core pillars that guide everything we do</p>
  </div>
  <div class="mv-grid" style="max-width:1100px;margin:0 auto">
    <div class="mv-card" style="background:#fff3e0">
      <div class="mv-icon" style="background:#ffe0b2"><i class="fa fa-bullseye" style="color:#ff9800"></i></div>
      <h3>Our Mission</h3>
      <p>To provide safe, affordable, and high-quality baby products that support the health and happiness of every child.</p>
    </div>
    <div class="mv-card" style="background:#fce4ec">
      <div class="mv-icon" style="background:#f8bbd0"><i class="fa fa-eye" style="color:#e91e63"></i></div>
      <h3>Our Vision</h3>
      <p>To become India's most loved and trusted baby care brand, making quality products accessible to every family.</p>
    </div>
    <div class="mv-card" style="background:#e8f5e9">
      <div class="mv-icon" style="background:#c8e6c9"><i class="fa fa-heart" style="color:#4caf50"></i></div>
      <h3>Our Promise</h3>
      <p>Every product we sell is tested for safety, made with love, and backed by our satisfaction guarantee.</p>
    </div>
    <div class="mv-card" style="background:#e3f2fd">
      <div class="mv-icon" style="background:#bbdefb"><i class="fa fa-leaf" style="color:#2196f3"></i></div>
      <h3>Eco Friendly</h3>
      <p>We are committed to sustainable packaging and eco-conscious sourcing for a better tomorrow.</p>
    </div>
  </div>
</div>

<!-- ── OUR VALUES ── -->
<div class="section">
  <div class="section-title">
    <h2>Our <span>Values</span></h2>
  </div>
  <div class="values-grid">
    <div class="val-card">
      <div class="val-icon" style="background:#fce4ec"><i class="fa fa-shield-alt" style="color:#e91e63"></i></div>
      <div><h4>Safety First</h4><p>All products meet strict safety standards for babies.</p></div>
    </div>
    <div class="val-card">
      <div class="val-icon" style="background:#e8f5e9"><i class="fa fa-star" style="color:#4caf50"></i></div>
      <div><h4>Quality</h4><p>We never compromise on the quality of our products.</p></div>
    </div>
    <div class="val-card">
      <div class="val-icon" style="background:#fff3e0"><i class="fa fa-hand-holding-heart" style="color:#ff9800"></i></div>
      <div><h4>Care</h4><p>We genuinely care about every baby and every family.</p></div>
    </div>
    <div class="val-card">
      <div class="val-icon" style="background:#e3f2fd"><i class="fa fa-truck" style="color:#2196f3"></i></div>
      <div><h4>Reliability</h4><p>Fast, on-time delivery you can always count on.</p></div>
    </div>
    <div class="val-card">
      <div class="val-icon" style="background:#f3e5f5"><i class="fa fa-comments" style="color:#9c27b0"></i></div>
      <div><h4>Support</h4><p>Friendly customer support available whenever you need.</p></div>
    </div>
    <div class="val-card">
      <div class="val-icon" style="background:#fce4ec"><i class="fa fa-tag" style="color:#e91e63"></i></div>
      <div><h4>Affordability</h4><p>Premium quality at prices every family can afford.</p></div>
    </div>
  </div>
</div>

<!-- ── MEET THE TEAM ── -->
<div style="background:#f9f9f9;padding:60px 20px">
  <div class="section-title">
    <h2>Meet the <span>Team</span></h2>
    <p>The passionate people behind Mumma's Care</p>
  </div>
  <div class="team-grid" style="max-width:1100px;margin:0 auto">
    <?php
    $team = [
      ['Priya Sharma',  'P', '#e91e63,#c2185b', 'Founder & CEO',      'A mother of two who started Mumma\'s Care to help parents find trusted baby products.'],
      ['Rahul Mehta',   'R', '#9c27b0,#7b1fa2', 'Head of Products',   'Ensures every product meets our strict quality and safety standards.'],
      ['Anjali Verma',  'A', '#ff9800,#f57c00', 'Customer Experience', 'Dedicated to making every parent\'s shopping experience smooth and joyful.'],
      ['Sneha Patel',   'S', '#4caf50,#388e3c', 'Marketing Lead',     'Spreads the word about safe baby care to families across India.'],
    ];
    foreach($team as $m): ?>
    <div class="team-card">
      <div class="team-avatar" style="background:linear-gradient(135deg,<?= $m[2] ?>)"><?= $m[0][0] ?></div>
      <h4><?= $m[0] ?></h4>
      <span><?= $m[2+1] ?></span>
      <p><?= $m[4] ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── REVIEWS ── -->
<div class="reviews-bg">
  <div class="section-title">
    <h2>What <span>Parents Say</span></h2>
    <p>Real reviews from real families</p>
  </div>
  <div class="reviews-grid">
    <?php
    $reviews = [
      ['Priya S.',  '../image offer/about.webp',  null,    5, "Mumma's Care has the best diapers! My baby stays dry and comfortable all night. Absolutely love the quality.", 'Mumbai'],
      ['Rohan K.',  null,                          'R',     5, "Fast delivery and amazing products. The baby gear is sturdy and well-made. Highly recommend to all new parents.", 'Delhi'],
      ['Anjali M.', '../image offer/about1.jpg',   null,    5, "The baby skincare range is so gentle on my little one's skin. No rashes, no irritation. We are loyal customers!", 'Bangalore'],
      ['Sneha T.',  null,                          'S',     4, "Ordered the celebration kit for my baby's first birthday — it was perfect! Great packaging and quick delivery.", 'Pune'],
      ['Kavya R.',  null,                          'K',     5, "Amazing variety of products. The toys are safe and my baby loves them. Will definitely order again!", 'Chennai'],
      ['Meera D.',  null,                          'M',     5, "Super fast delivery and the products are exactly as described. Mumma's Care is my go-to store for baby products.", 'Hyderabad'],
    ];
    foreach($reviews as $r): ?>
    <div class="review-card">
      <div class="stars">
        <?php for($s=1;$s<=5;$s++) echo '<i class="fa'.($s<=$r[3]?'s':' far').' fa-star"></i>'; ?>
      </div>
      <p>"<?= $r[4] ?>"</p>
      <div class="reviewer">
        <?php if($r[1]): ?>
          <img class="ava" src="<?= $r[1] ?>" alt="<?= $r[0] ?>">
        <?php else: ?>
          <div class="ava-init"><?= $r[2] ?></div>
        <?php endif; ?>
        <div class="reviewer-info">
          <strong><?= $r[0] ?></strong>
          <span><?= $r[5] ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── CTA ── -->
<div class="cta">
  <h2>Join the Mumma's Care Family</h2>
  <p>Thousands of happy parents trust us. Start shopping today.</p>
  <a href="category.php"><i class="fa fa-shopping-bag"></i> Shop Now</a>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
