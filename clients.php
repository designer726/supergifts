<?php
$pagename = basename($_SERVER['PHP_SELF']);

/* Clients hero banner — admin-managed via Banner Management (slot 17).
   Admin can upload either:
   - a plain photo (no text baked in) -> shown as the background of the
     coded hero below, with the live heading/stats/buttons overlaid on it; or
   - a ready-made full banner graphic (design + text already baked in)
     -> shown edge-to-edge as-is, with the coded hero's text hidden so
     nothing doubles up.
   Falls back to the bundled photo + coded hero when nothing is uploaded. */
$clientsHeroImg    = 'images/collaborate.jpg';
$clientsHeroCustom = false;
$clientsHeroFull   = false;
$clb = @new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
if ($clb && !$clb->connect_error) {
    $clr = $clb->query("SELECT file_path, display_mode FROM banners WHERE slot=17 AND status=1 AND file_path<>'' LIMIT 1");
    if ($clr && ($clrow = $clr->fetch_assoc()) && !empty($clrow['file_path'])) {
        $clientsHeroImg    = $clrow['file_path'];
        $clientsHeroCustom = true;
        $clientsHeroFull   = (($clrow['display_mode'] ?? 'photo') === 'full');
    }
}

/* Logos shown in the "Trusted by Leading Brands" grid — swap the image
   paths in sgipl-manage or here if the underlying files change. */
$clientLogos = [
    ['name' => 'Amazon',         'img' => 'images/clients-logos/with-bg/client-1.jpg'],
    ['name' => 'Ambuja Cement',  'img' => 'images/clients-logos/with-bg/client-2.jpg'],
    ['name' => 'ACC',            'img' => 'images/clients-logos/with-bg/client-3.jpg'],
    ['name' => 'Aurobindo',      'img' => 'images/clients-logos/with-bg/client-4.jpg'],
    ['name' => "Dr. Reddy's",    'img' => 'images/clients-logos/with-bg/client-5.jpg'],
    ['name' => 'UltraTech',      'img' => 'images/clients-logos/with-bg/client-6.jpg'],
    ['name' => 'Adani',          'img' => 'images/clients-logos/with-bg/client-7.jpg'],
    ['name' => 'Hindware',       'img' => 'images/clients-logos/with-bg/client-8.jpg'],
    ['name' => 'TechnipFMC',     'img' => 'images/clients-logos/with-bg/client-9.jpg'],
    ['name' => 'TEKsystems',     'img' => 'images/clients-logos/with-bg/client-10.jpg'],
];

/* Client case-study testimonials — admin-managed via Client Testimonials in
   the admin panel. Falls back to a bundled default testimonial when the
   table is empty/unreachable. A headline's **text** becomes a bold +
   underlined <span class="cl-hl"> on the page. */
$clientTestimonials = [];
if ($clb && !$clb->connect_error) {
    $ctr = $clb->query("SELECT headline, content, client_name, client_location, avatar, image FROM client_testimonials WHERE status=1 ORDER BY sequence ASC, id DESC");
    if ($ctr) {
        while ($ctrow = $ctr->fetch_assoc()) {
            $clientTestimonials[] = [
                'headline' => $ctrow['headline'],
                'quote'    => preg_split('/\r\n|\r|\n/', trim($ctrow['content']), -1, PREG_SPLIT_NO_EMPTY),
                'name'     => $ctrow['client_name'],
                'location' => $ctrow['client_location'],
                'avatar'   => $ctrow['avatar'],
                'image'    => $ctrow['image'],
            ];
        }
    }
}
if ($clb) $clb->close();

if (!$clientTestimonials) {
    $clientTestimonials = [
        [
            'headline' => 'SGIPL is trusted by **1,000+** customers.',
            'quote' => [
                "My name is Dr Reshma Jhaveri and I was in charge of selecting an item for the delegates at Bombay Ophthalmologists' Association Focus 2024.",
                "I have worked with Rehan and his company earlier as well, and this time too when I got this opportunity the first person who came to my mind was Rehan.",
                "As always he suggested this laptop bag from Nautica with the most amazing colours that I totally fell in love with, and when I showed it to my managing committee there was a positive consensus immediately.",
                "All the members at the conference too really appreciated the bags and gave very good feedback.",
                "Rehan helped in transporting all the bags from the warehouse to the conference venue and did it with ease!",
                "Would like to compliment the entire team for great service and product.",
            ],
            'name'     => 'Dr Reshma Jhaveri',
            'location' => 'Mumbai',
            'avatar'   => '',
            'image'    => 'images/onboardingkit2.png',
        ],
    ];
}

/* Turns "SGIPL is trusted by **1,000+** customers." into HTML with the
   **wrapped** part as a bold+underlined highlight span. */
function cl_render_headline($headline) {
    $escaped = htmlspecialchars($headline);
    return preg_replace('/\*\*(.+?)\*\*/', '<span class="cl-hl">$1</span>', $escaped);
}
?>
<!DOCTYPE html>

<html lang="en">
    <head>

<?php include('common/head.php'); ?>
    </head>
    <body class="appear-animate">

        <!-- Page Loader -->
        <div class="page-loader">
            <div class="loader">Loading...</div>
        </div>
        <!-- End Page Loader -->

        <!-- Page Wrap -->
        <div class="page" id="top">

           <?php include('common/nav.php'); ?>

           <main id="main">

           <style>
           /* ============ CLIENTS PAGE — scoped styles ============ */
           .cl-wrap { background:#F7F8FC; color:#1f2340; overflow:hidden; }
           .cl-wrap a { text-decoration:none; }
           .cl-container { max-width:1200px; margin:0 auto; padding:0 24px; }

           /* ── Hero: ready-made banner graphic (Slot 17, "full" display mode) ── */
           .cl-hero-full { width:100%; line-height:0; font-size:0; background:#F7F8FC; }
           .cl-hero-full img { width:100%; height:auto; display:block; }

           /* ── Hero: plain photo + coded heading/stats (Slot 17, "photo" mode / default) ── */
           .cl-hero {
               position:relative;
               overflow:hidden;
               padding:72px 0;
               background:#F7F8FC;
           }
           .cl-hero-photo {
               position:absolute; inset:0; z-index:0;
               background:url('<?= htmlspecialchars($clientsHeroImg) ?>') center right / cover no-repeat;
           }
           .cl-hero-photo::before {
               content:""; position:absolute; inset:0;
               background:linear-gradient(100deg,#F7F8FC 0%,#F7F8FC 22%,rgba(247,248,252,.94) 34%,rgba(247,248,252,.6) 46%,rgba(247,248,252,.2) 60%,rgba(247,248,252,0) 72%);
           }
           .cl-hero h1, .cl-hero-sub { text-shadow:0 1px 14px rgba(247,248,252,.9); }
           .cl-hero-corner { position:absolute; right:0; bottom:0; width:180px; height:180px; z-index:1; overflow:hidden; pointer-events:none; }
           .cl-hero-corner::before {
               content:""; position:absolute; right:-90px; bottom:-90px; width:190px; height:190px;
               background:#191A45; transform:rotate(45deg);
           }
           .cl-hero-corner::after {
               content:""; position:absolute; right:26px; bottom:-30px; width:22px; height:170px;
               background:#FFD400; transform:rotate(45deg); border-radius:3px;
           }
           .cl-hero-grid { position:relative; z-index:2; display:grid; grid-template-columns:1.4fr 0.9fr; gap:40px; align-items:center; }
           .cl-badge { display:inline-flex; align-items:center; gap:8px; background:#191A45; color:#fff; font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:6px 14px; border-radius:20px; margin-bottom:22px; }
           .cl-badge::before { content:""; width:7px; height:7px; border-radius:50%; background:#FFD400; }
           .cl-hero h1 { font-family:'Poppins',sans-serif; font-size:42px; line-height:1.16; font-weight:800; color:#191A45; margin:0 0 18px; max-width:560px; }
           .cl-hero h1 .cl-hl { color:#D4A900; text-decoration:underline; text-decoration-color:#FFD400; text-decoration-thickness:4px; text-underline-offset:6px; }
           .cl-hero-sub { font-size:15px; line-height:1.75; color:#565a78; max-width:440px; margin:0 0 28px; }
           .cl-hero-btns { display:flex; gap:14px; flex-wrap:wrap; }
           .cl-btn-gold { display:inline-flex; align-items:center; gap:10px; background:#FFD400; color:#1B1B4B; font-weight:700; font-size:14px; padding:13px 24px; border-radius:10px; border:0; cursor:pointer; transition:transform .2s, box-shadow .2s; }
           .cl-btn-gold:hover { transform:translateY(-2px); box-shadow:0 10px 26px rgba(255,212,0,.35); }
           .cl-btn-ghost { display:inline-flex; align-items:center; gap:10px; background:#fff; color:#191A45; font-weight:600; font-size:14px; padding:13px 24px; border-radius:10px; border:1px solid #D9DCE8; cursor:pointer; transition:background .2s, box-shadow .2s; }
           .cl-btn-ghost:hover { background:#F4F5FB; box-shadow:0 8px 18px rgba(25,26,69,.08); }
           .cl-btn-gold svg, .cl-btn-ghost svg { width:15px; height:15px; }

           .cl-hero-stats { position:relative; z-index:2; display:flex; flex-direction:column; gap:14px; }
           .cl-stat { display:flex; align-items:center; gap:14px; background:#fff; border:1px solid #E7E9F2; border-radius:14px; padding:14px 20px; box-shadow:0 16px 34px -18px rgba(25,26,69,.28); }
           .cl-stat-ic { flex-shrink:0; width:38px; height:38px; border-radius:50%; background:rgba(255,212,0,.16); color:#D4A900; display:flex; align-items:center; justify-content:center; }
           .cl-stat-ic svg { width:18px; height:18px; }
           .cl-stat .num { font-family:'Poppins',sans-serif; font-size:22px; font-weight:800; color:#191A45; line-height:1.1; }
           .cl-stat .lbl { font-size:12px; color:#7a7fa0; margin-top:2px; }

           /* ── Trusted by Leading Brands ── */
           .cl-brands { padding:76px 0 84px; text-align:center; }
           .cl-eyebrow { display:flex; align-items:center; justify-content:center; gap:14px; color:#D4A900; font-size:12px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; margin-bottom:14px; }
           .cl-eyebrow::before, .cl-eyebrow::after { content:""; width:34px; height:2px; background:#FFD400; border-radius:2px; }
           .cl-brands h2 { font-family:'Poppins',sans-serif; font-size:32px; font-weight:800; color:#191A45; margin:0 0 14px; }
           .cl-brands-lead { max-width:620px; margin:0 auto 46px; font-size:15px; line-height:1.7; color:#6b7091; }
           .cl-logo-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; max-width:1000px; margin:0 auto; }
           .cl-logo-card { background:#fff; border:1px solid #E7E9F2; border-radius:14px; height:104px; display:flex; align-items:center; justify-content:center; padding:18px; transition:box-shadow .25s, transform .25s; }
           .cl-logo-card:hover { box-shadow:0 16px 34px rgba(25,26,69,.1); transform:translateY(-4px); }
           .cl-logo-card img { max-width:100%; max-height:100%; object-fit:contain; }

           /* ── Testimonial ── */
           .cl-testimonial-sec { padding:0 0 90px; }
           .cl-t-card { max-width:1120px; margin:0 auto; background:#fff; border-radius:24px; box-shadow:0 34px 80px -42px rgba(25,26,69,.32); border:1px solid #eef0f7; display:grid; grid-template-columns:1.35fr 1fr; overflow:hidden; }
           .cl-t-body { padding:54px; }
           .cl-quote-mark { font-family:Georgia,'Times New Roman',serif; font-size:60px; font-weight:700; line-height:1; color:#FFD400; margin-bottom:6px; }
           .cl-t-body h3 { font-size:24px; font-weight:800; color:#191A45; margin:0 0 22px; }
           .cl-t-body h3 .cl-hl { color:#191A45; text-decoration:underline; text-decoration-color:#FFD400; text-decoration-thickness:3px; text-underline-offset:4px; }
           .cl-t-body p { color:#565a78; font-size:14.5px; line-height:1.85; margin:0 0 14px; }
           .cl-t-divider { width:56px; height:3px; background:#FFD400; border-radius:2px; margin:22px 0 22px; }
           .cl-t-footer { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
           .cl-t-author { display:flex; align-items:center; gap:12px; }
           .cl-t-avatar, .cl-t-avatar-fallback { width:46px; height:46px; border-radius:50%; flex-shrink:0; }
           .cl-t-avatar { object-fit:cover; }
           .cl-t-avatar-fallback { background:#241C6B; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:16px; }
           .cl-t-name { font-weight:700; color:#191A45; font-size:14px; }
           .cl-t-loc { color:#8a8fa8; font-size:12.5px; margin-top:1px; }
           .cl-t-nav { display:flex; gap:10px; }
           .cl-t-nav button { width:38px; height:38px; border-radius:50%; border:1px solid #E5E7F0; background:#fff; color:#191A45; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.2s; }
           .cl-t-nav button svg { width:16px; height:16px; }
           .cl-t-nav button:hover { background:#191A45; color:#fff; border-color:#191A45; }

           .cl-t-media { position:relative; background:#F4F5FB; display:flex; align-items:center; justify-content:center; padding:40px; }
           .cl-t-media::before { content:""; position:absolute; width:66%; height:66%; background:#FFD400; border-radius:26px; transform:rotate(8deg); z-index:0; }
           .cl-t-media img { position:relative; z-index:1; max-width:78%; max-height:78%; object-fit:contain; border-radius:14px; box-shadow:0 22px 44px rgba(20,20,60,.18); background:#fff; }

           @media (max-width:991px) {
               .cl-hero { padding:56px 0; }
               .cl-hero-corner { width:130px; height:130px; }
               .cl-hero-grid { grid-template-columns:1fr; }
               .cl-hero h1 { font-size:32px; }
               .cl-hero-stats { flex-direction:row; flex-wrap:wrap; }
               .cl-stat { flex:1 1 150px; }
               .cl-logo-grid { grid-template-columns:repeat(3,1fr); }
               .cl-t-card { grid-template-columns:1fr; }
               .cl-t-media { padding:36px; min-height:220px; }
               .cl-t-body { padding:40px; }
           }
           @media (max-width:600px) {
               .cl-container { padding:0 16px; }
               .cl-hero { padding:32px 0; }
               .cl-hero-corner { width:90px; height:90px; }
               .cl-hero-corner::before { right:-55px; bottom:-55px; width:120px; height:120px; }
               .cl-hero-corner::after { right:14px; bottom:-22px; width:14px; height:110px; }
               .cl-hero-grid { gap:22px; }
               .cl-badge { margin-bottom:14px; }
               .cl-hero h1 { font-size:24px; margin:0 0 12px; }
               .cl-hero-sub { font-size:13.5px; margin:0 0 20px; }
               .cl-hero-btns { width:100%; }
               .cl-btn-gold, .cl-btn-ghost { flex:1 1 auto; justify-content:center; padding:12px 18px; font-size:13px; }
               .cl-hero-stats { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
               .cl-stat { padding:10px 12px; gap:10px; }
               .cl-stat-ic { width:32px; height:32px; }
               .cl-stat-ic svg { width:15px; height:15px; }
               .cl-stat .num { font-size:17px; }
               .cl-stat .lbl { font-size:11px; }
               .cl-hero-stats .cl-stat:last-child { grid-column:1 / -1; }

               .cl-brands { padding:52px 0 56px; }
               .cl-brands h2 { font-size:24px; }
               .cl-logo-grid { grid-template-columns:repeat(2,1fr); gap:14px; }
               .cl-logo-card { height:88px; padding:14px; border-radius:12px; }

               .cl-t-body { padding:28px 22px; }
               .cl-quote-mark { font-size:48px; }
               .cl-t-body h3 { font-size:20px; }
               .cl-t-media { padding:26px; min-height:180px; }
               .cl-t-footer { align-items:flex-start; }
           }
           </style>

           <div class="cl-wrap">

               <!-- ======== HERO ======== -->
               <?php if ($clientsHeroFull): ?>
               <section class="cl-hero-full">
                   <img src="<?= htmlspecialchars($clientsHeroImg) ?>" alt="Our Clients — Trusted by 200+ Leading Companies">
               </section>
               <?php else: ?>
               <section class="cl-hero">
                   <div class="cl-hero-photo"></div>
                   <div class="cl-hero-corner"></div>
                   <div class="cl-container">
                       <div class="cl-hero-grid">
                           <div>
                               <div class="cl-badge">Our Clients</div>
                               <h1>Trusted by <span class="cl-hl">200+</span> Leading Companies</h1>
                               <p class="cl-hero-sub">Our core principles: We place paramount importance on quality, trust, and teamwork as the fundamental cornerstones of our methodology.</p>
                               <div class="cl-hero-btns">
                                   <a href="contact" class="cl-btn-gold">Become a Partner
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                   </a>
                                   <a href="reviews" class="cl-btn-ghost">
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                       See Reviews
                                   </a>
                               </div>
                           </div>

                           <div class="cl-hero-stats">
                               <div class="cl-stat">
                                   <div class="cl-stat-ic">
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                   </div>
                                   <div><div class="num">200+</div><div class="lbl">Clients Served</div></div>
                               </div>
                               <div class="cl-stat">
                                   <div class="cl-stat-ic">
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                   </div>
                                   <div><div class="num">1000+</div><div class="lbl">Resellers</div></div>
                               </div>
                               <div class="cl-stat">
                                   <div class="cl-stat-ic">
                                       <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                   </div>
                                   <div><div class="num">4.9</div><div class="lbl">Star Rating</div></div>
                               </div>
                           </div>
                       </div>
                   </div>
               </section>
               <?php endif; ?>

               <!-- ======== TRUSTED BY LEADING BRANDS ======== -->
               <section class="cl-brands">
                   <div class="cl-container">
                       <div class="cl-eyebrow">Our Valued Clients</div>
                       <h2>Trusted by Leading Brands</h2>
                       <p class="cl-brands-lead">We are proud to work with some of the most respected brands across industries, delivering quality gifting and distribution solutions.</p>

                       <div class="cl-logo-grid">
                           <?php foreach ($clientLogos as $logo): ?>
                           <div class="cl-logo-card">
                               <img src="<?= htmlspecialchars($logo['img']) ?>" alt="<?= htmlspecialchars($logo['name']) ?>" loading="lazy">
                           </div>
                           <?php endforeach; ?>
                       </div>
                   </div>
               </section>

               <!-- ======== TESTIMONIAL ======== -->
               <section class="cl-testimonial-sec">
                   <div class="cl-container">
                       <?php $tCount = count($clientTestimonials); foreach ($clientTestimonials as $i => $t): ?>
                       <div class="cl-t-card" data-t-slide="<?= $i ?>" <?= $i > 0 ? 'style="display:none;"' : '' ?>>
                           <div class="cl-t-body">
                               <div class="cl-quote-mark" aria-hidden="true">&ldquo;</div>
                               <h3><?= cl_render_headline($t['headline']) ?></h3>
                               <?php foreach ($t['quote'] as $para): ?>
                               <p><?= htmlspecialchars($para) ?></p>
                               <?php endforeach; ?>

                               <div class="cl-t-divider"></div>

                               <div class="cl-t-footer">
                                   <div class="cl-t-author">
                                       <?php if (!empty($t['avatar'])): ?>
                                       <img class="cl-t-avatar" src="<?= htmlspecialchars($t['avatar']) ?>" alt="<?= htmlspecialchars($t['name']) ?>">
                                       <?php else: ?>
                                       <div class="cl-t-avatar-fallback"><?= htmlspecialchars(mb_substr($t['name'], 0, 1)) ?></div>
                                       <?php endif; ?>
                                       <div>
                                           <div class="cl-t-name"><?= htmlspecialchars($t['name']) ?></div>
                                           <div class="cl-t-loc"><?= htmlspecialchars($t['location']) ?></div>
                                       </div>
                                   </div>
                                   <?php if ($tCount > 1): ?>
                                   <div class="cl-t-nav">
                                       <button type="button" onclick="clTestimonialNav(-1)" aria-label="Previous">
                                           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                                       </button>
                                       <button type="button" onclick="clTestimonialNav(1)" aria-label="Next">
                                           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                       </button>
                                   </div>
                                   <?php endif; ?>
                               </div>
                           </div>
                           <div class="cl-t-media">
                               <img src="<?= htmlspecialchars($t['image']) ?>" alt="Gift supplied to <?= htmlspecialchars($t['name']) ?>">
                           </div>
                       </div>
                       <?php endforeach; ?>
                   </div>
               </section>

           </div>

           <?php if (count($clientTestimonials) > 1): ?>
           <script>
           (function() {
               var slides = document.querySelectorAll('[data-t-slide]');
               var current = 0;
               window.clTestimonialNav = function(dir) {
                   slides[current].style.display = 'none';
                   current = (current + dir + slides.length) % slides.length;
                   slides[current].style.display = 'grid';
               };
           })();
           </script>
           <?php endif; ?>

       </main>

      <?php include('common/footer.php'); ?>
    </body>
</html>
