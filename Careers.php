<?php
$pagename = basename($_SERVER['PHP_SELF']);
require_once('sgipl-manage/includes/db.php'); // Your existing DB connection file

/* Careers hero banner — admin-managed via Banner Management (slot 19).
   Same "photo" (page draws its own heading/button on top) vs "full"
   (ready-made graphic, shown as-is) modes used on the Blog/Clients pages.
   Falls back to a plain gradient hero (no photo) when nothing is uploaded. */
$careersHeroImg    = '';
$careersHeroCustom = false;
$careersHeroFull   = false;
$crb = $conn->query("SELECT file_path, display_mode FROM banners WHERE slot=19 AND status=1 AND file_path<>'' LIMIT 1");
if ($crb && ($crbrow = $crb->fetch_assoc()) && !empty($crbrow['file_path'])) {
    $careersHeroImg    = $crbrow['file_path'];
    $careersHeroCustom = true;
    $careersHeroFull   = (($crbrow['display_mode'] ?? 'photo') === 'full');
}

/* Employee reviews for the "What Our People Say" carousel — admin-managed
   via Employee Reviews in the admin panel. Falls back to a bundled default
   review when the table is empty/unreachable. */
$employeeReviews = [];
$err = $conn->query("SELECT photo, quote, employee_name, role FROM employee_testimonials WHERE status=1 ORDER BY sequence ASC, id DESC");
if ($err) {
    while ($errow = $err->fetch_assoc()) {
        $employeeReviews[] = [
            'photo' => $errow['photo'],
            'quote' => $errow['quote'],
            'name'  => $errow['employee_name'],
            'role'  => $errow['role'],
        ];
    }
}
if (!$employeeReviews) {
    $employeeReviews = [
        [
            'photo' => '',
            'quote' => 'Super Gifts has given me the perfect platform to learn, grow and make a real impact. The supportive team and open culture make it a great place to work and build your career.',
            'name'  => 'Arbaz Jalmani',
            'role'  => 'Senior Designer',
        ],
    ];
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
           @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&display=swap');
           /* ============ CAREERS PAGE — scoped styles ============ */
           .cr-wrap { background:#fff; color:#1f2340; overflow:hidden; }
           .cr-wrap a { text-decoration:none; }
           .cr-container { max-width:1200px; margin:0 auto; padding:0 24px; }

           /* ── Hero ── */
           .cr-hero { position:relative; overflow:hidden; background:linear-gradient(120deg,#1B1B4B 0%,#241C6B 60%,#1B1B4B 100%); padding:64px 0; }
           .cr-hero-grid { position:relative; z-index:3; display:grid; grid-template-columns:1.15fr 1fr; gap:40px; align-items:center; }
           .cr-eyebrow-line { display:inline-flex; align-items:center; gap:10px; color:#FFD400; font-size:12px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; margin-bottom:18px; }
           .cr-eyebrow-line::before { content:""; width:26px; height:3px; background:#FFD400; border-radius:2px; }
           .cr-hero h1 { font-family:'Poppins',sans-serif; font-size:44px; line-height:1.16; font-weight:800; color:#fff; margin:0 0 18px; }
           .cr-hero h1 .cr-hl { color:#FFD400; }
           .cr-hero-sub { font-size:15px; line-height:1.75; color:rgba(255,255,255,.68); max-width:460px; margin:0 0 30px; }
           .cr-btn-red { display:inline-flex; align-items:center; gap:10px; background:#D0021B; color:#fff; font-weight:700; font-size:14px; padding:14px 28px; border-radius:10px; border:0; cursor:pointer; transition:transform .2s, box-shadow .2s; }
           .cr-btn-red:hover { transform:translateY(-2px); box-shadow:0 10px 26px rgba(208,2,27,.35); }
           .cr-btn-red svg { width:15px; height:15px; }

           .cr-hero-photo-wrap { position:relative; z-index:2; height:320px; border-radius:20px; overflow:hidden; }
           .cr-hero-photo-wrap img { width:100%; height:100%; object-fit:cover; display:block; }
           .cr-hero-photo-fallback { width:100%; height:100%; border-radius:20px; background:linear-gradient(135deg,rgba(255,255,255,.08),rgba(255,255,255,.02)); border:1px dashed rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; }
           .cr-hero-photo-fallback svg { width:64px; height:64px; color:rgba(255,255,255,.25); }

           .cr-hero-circle { position:absolute; left:-40px; bottom:-40px; width:180px; height:180px; border-radius:50%; background:#D0021B; z-index:1; opacity:.9; }
           .cr-hero-quarter { position:absolute; right:0; bottom:0; width:150px; height:150px; background:#FFD400; border-top-left-radius:150px; z-index:1; }
           .cr-hero-script { position:absolute; top:26px; right:70px; z-index:3; font-family:'Caveat',cursive; font-size:30px; color:#fff; line-height:1.15; transform:rotate(-6deg); text-align:right; }
           .cr-hero-script span { display:block; }
           .cr-hero-dots { position:absolute; top:30px; right:16px; z-index:2; width:34px; height:34px; background-image:radial-gradient(rgba(255,255,255,.35) 1.5px, transparent 1.5px); background-size:8px 8px; }

           /* Ready-made banner mode */
           .cr-hero-full { width:100%; line-height:0; font-size:0; background:#1B1B4B; }
           .cr-hero-full img { width:100%; height:auto; display:block; }

           /* ── Intro: "More Than a Job, A Place to Grow" ── */
           .cr-intro { padding:70px 0; }
           .cr-intro-grid { display:grid; grid-template-columns:0.9fr 1fr; gap:50px; align-items:start; }
           .cr-eyebrow-gold { display:flex; align-items:center; gap:10px; color:#D4A900; font-size:12px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; margin-bottom:16px; }
           .cr-eyebrow-gold::before { content:""; width:26px; height:3px; background:#FFD400; border-radius:2px; }
           .cr-intro h2 { font-family:'Poppins',sans-serif; font-size:36px; font-weight:800; line-height:1.22; color:#1B2A6B; margin:0 0 16px; }
           .cr-intro h2 .cr-accent { color:#5B21B6; }
           .cr-intro-underline { width:56px; height:4px; background:#FFD400; border-radius:2px; }
           .cr-intro-body { border-left:2px solid #E7E9F2; padding-left:36px; color:#2451D6; font-size:15px; line-height:1.85; }

           /* ── Details card (culture / growth / apply) ── */
           .cr-details { padding:0 0 70px; }
           .cr-details-card { max-width:900px; margin:0 auto; background:#F7F8FC; border:1px solid #E7E9F2; border-radius:20px; padding:44px 48px; }
           .cr-details-card p { color:#4b4f6b; font-size:14.5px; line-height:1.9; margin:0 0 18px; }
           .cr-details-card p:last-child { margin-bottom:0; }
           .cr-details-card a { color:#D0021B; font-weight:700; }

           /* ── What Our People Say ── */
           .cr-reviews { background:#EEF1FB; padding:76px 0 84px; text-align:center; }
           .cr-reviews h2 { font-family:'Poppins',sans-serif; font-size:34px; font-weight:800; color:#191A45; margin:0 0 14px; }
           .cr-reviews h2 .cr-accent { color:#6C3CE9; }
           .cr-reviews-lead { max-width:560px; margin:0 auto 46px; font-size:15px; line-height:1.7; color:#6b7091; }

           .cr-carousel { position:relative; max-width:980px; margin:0 auto; display:flex; align-items:center; justify-content:center; gap:18px; }
           .cr-nav-btn { flex-shrink:0; width:44px; height:44px; border-radius:50%; border:1px solid #E0E3F0; background:#fff; color:#191A45; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:.2s; box-shadow:0 8px 20px -10px rgba(25,26,69,.3); }
           .cr-nav-btn:hover { background:#191A45; color:#fff; border-color:#191A45; }
           .cr-nav-btn svg { width:18px; height:18px; }

           .cr-peek { flex-shrink:0; width:72px; height:72px; border-radius:50%; overflow:hidden; opacity:.5; filter:grayscale(40%); border:3px solid #fff; box-shadow:0 6px 16px rgba(25,26,69,.12); transition:opacity .25s; }
           .cr-peek img { width:100%; height:100%; object-fit:cover; display:block; }
           .cr-peek-fallback { width:100%; height:100%; background:#241C6B; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:22px; }

           .cr-t-card { flex:1 1 auto; max-width:680px; background:#fff; border-radius:22px; box-shadow:0 30px 70px -36px rgba(25,26,69,.32); border:1px solid #eef0f7; display:grid; grid-template-columns:0.8fr 1.2fr; overflow:hidden; text-align:left; }
           .cr-t-photo { position:relative; background:#F4F5FB; }
           .cr-t-photo img { width:100%; height:100%; object-fit:cover; display:block; min-height:220px; }
           .cr-t-photo-fallback { width:100%; height:100%; min-height:220px; background:linear-gradient(135deg,#241C6B,#1B1B4B); color:#fff; display:flex; align-items:center; justify-content:center; font-size:44px; font-weight:800; }
           .cr-t-body { padding:36px 34px; display:flex; flex-direction:column; }
           .cr-quote-mark { font-family:Georgia,'Times New Roman',serif; font-size:46px; font-weight:700; line-height:1; color:#FFD400; margin-bottom:8px; }
           .cr-t-quote { color:#4b4f6b; font-size:14.5px; line-height:1.85; margin:0 0 20px; }
           .cr-t-name { font-weight:800; color:#191A45; font-size:15px; }
           .cr-t-role { color:#D0021B; font-size:13px; font-weight:700; margin-top:2px; }

           .cr-dots { display:flex; gap:8px; justify-content:center; margin-top:34px; }
           .cr-dot { width:8px; height:8px; border-radius:50%; background:#D5D8EA; cursor:pointer; transition:.2s; border:0; padding:0; }
           .cr-dot.active { background:#6C3CE9; width:22px; border-radius:4px; }

           @media (max-width:991px) {
               .cr-hero { padding:52px 0; }
               .cr-hero-grid { grid-template-columns:1fr; }
               .cr-hero h1 { font-size:34px; }
               .cr-hero-photo-wrap { height:260px; }
               .cr-hero-script { display:none; }
               .cr-intro-grid { grid-template-columns:1fr; gap:24px; }
               .cr-intro-body { border-left:0; padding-left:0; border-top:2px solid #E7E9F2; padding-top:24px; }
               .cr-details-card { padding:34px 30px; }
               .cr-carousel { flex-wrap:wrap; }
               .cr-peek { display:none; }
               .cr-t-card { grid-template-columns:1fr; }
               .cr-t-photo img, .cr-t-photo-fallback { min-height:180px; }
           }
           @media (max-width:600px) {
               .cr-container { padding:0 16px; }
               .cr-hero { padding:40px 0; }
               .cr-hero h1 { font-size:27px; }
               .cr-hero-sub { font-size:13.5px; }
               .cr-btn-red { width:100%; justify-content:center; }
               .cr-hero-photo-wrap { height:200px; }
               .cr-hero-circle { width:120px; height:120px; left:-30px; bottom:-30px; }
               .cr-hero-quarter { width:100px; height:100px; }

               .cr-intro { padding:48px 0; }
               .cr-intro h2 { font-size:27px; }
               .cr-details-card { padding:26px 20px; border-radius:16px; }

               .cr-reviews { padding:52px 0 56px; }
               .cr-reviews h2 { font-size:25px; }
               .cr-t-body { padding:26px 22px; }
               .cr-quote-mark { font-size:38px; }
           }
           </style>

           <div class="cr-wrap">

               <!-- ======== HERO ======== -->
               <?php if ($careersHeroFull): ?>
               <section class="cr-hero-full" id="careers">
                   <img src="<?= htmlspecialchars($careersHeroImg) ?>" alt="Build Your Career With Super Gifts">
               </section>
               <?php else: ?>
               <section class="cr-hero" id="careers">
                   <div class="cr-hero-circle"></div>
                   <div class="cr-hero-quarter"></div>
                   <div class="cr-hero-dots"></div>
                   <div class="cr-hero-script"><span>Grow</span><span>Learn</span><span>Belong</span></div>
                   <div class="cr-container">
                       <div class="cr-hero-grid">
                           <div>
                               <div class="cr-eyebrow-line">Join Our Team</div>
                               <h1>Build Your Career<br>With <span class="cr-hl">Super Gifts</span></h1>
                               <p class="cr-hero-sub">Be part of a team that values people, encourages growth and turns ideas into meaningful impact.</p>
                               <a href="#about" class="cr-btn-red">Explore Opportunities
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                               </a>
                           </div>
                           <div class="cr-hero-photo-wrap">
                               <?php if ($careersHeroCustom): ?>
                               <img src="<?= htmlspecialchars($careersHeroImg) ?>" alt="Team at Super Gifts">
                               <?php else: ?>
                               <div class="cr-hero-photo-fallback">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                               </div>
                               <?php endif; ?>
                           </div>
                       </div>
                   </div>
               </section>
               <?php endif; ?>
               <!-- ======== END HERO ======== -->

               <!-- ======== INTRO: MORE THAN A JOB ======== -->
               <section class="cr-intro">
                   <div class="cr-container">
                       <div class="cr-intro-grid">
                           <div>
                               <div class="cr-eyebrow-gold">Our Careers</div>
                               <h2>More Than a Job,<br><span class="cr-accent">A Place to Grow</span></h2>
                               <div class="cr-intro-underline"></div>
                           </div>
                           <div class="cr-intro-body">
                               <p>At Super Gifts, we believe our people are our greatest strength. Join us to be a part of a dynamic team where you can grow your skills, work on exciting projects, collaborate with talented people and contribute to a business that values innovation and creates meaningful opportunities for the future.</p>
                           </div>
                       </div>
                   </div>
               </section>
               <!-- ======== END INTRO ======== -->

               <!-- ======== DETAILS / HOW TO APPLY ======== -->
               <section class="cr-details" id="about">
                   <div class="cr-container">
                       <div class="cr-details-card">
                           <p>Join the team at SUPER GIFTS (INDIA) PRIVATE LIMITED, where we embody a culture of passion, innovation, and excellence. We are a group of dedicated individuals who are committed to redefining the gifting experience in India. At SUPER GIFTS, we believe in fostering an environment that encourages creativity, strategic thinking, and personal growth.</p>

                           <p>As a rapidly growing company, we are constantly seeking talented individuals who are passionate about making a difference. Whether you're a seasoned professional or a recent graduate, there are ample opportunities for you to thrive and excel at SUPER GIFTS. We value diversity and inclusivity, and we welcome candidates from all backgrounds to join our team.</p>

                           <p>At SUPER GIFTS, we believe in investing in our employees' development and growth. We offer comprehensive training programs, mentorship opportunities, and ongoing support to help you reach your full potential. With a focus on career advancement and progression, we provide a clear path for professional growth and development within the company.</p>

                           <p>We understand that our success is driven by the collective efforts of our team members. That's why we foster a collaborative and supportive work environment where everyone's contributions are valued and appreciated. At SUPER GIFTS, you'll have the opportunity to work alongside talented individuals who share your passion for excellence and innovation.</p>

                           <p>In addition to competitive salaries and benefits, SUPER GIFTS offers a range of perks and incentives to reward our employees for their hard work and dedication. From flexible work arrangements to employee discounts on our products and services, we strive to create a positive and rewarding work experience for all our team members.</p>

                           <p>If you're looking for a challenging and rewarding career opportunity in the exciting world of gifting, SUPER GIFTS (INDIA) PRIVATE LIMITED is the place to be. Join us as we continue to redefine the art of gift-giving and make a positive impact on the lives of our customers.</p>

                           <p>To apply, please share your resume with us at <a href="mailto:info@supergifts.in">info@supergifts.in</a>. We look forward to hearing from you!</p>
                       </div>
                   </div>
               </section>
               <!-- ======== END DETAILS ======== -->

               <!-- ======== WHAT OUR PEOPLE SAY ======== -->
               <section class="cr-reviews">
                   <div class="cr-container">
                       <div class="cr-eyebrow-gold" style="justify-content:center;">Employees Review</div>
                       <h2>What Our <span class="cr-accent">People Say</span></h2>
                       <p class="cr-reviews-lead">Real stories. Real experiences. Hear from our team members about their journey at Super Gifts.</p>

                       <div class="cr-carousel">
                           <?php if (count($employeeReviews) > 1): ?>
                           <button type="button" class="cr-nav-btn" id="crPrevBtn" aria-label="Previous">
                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                           </button>
                           <div class="cr-peek" id="crPeekPrev"></div>
                           <?php endif; ?>

                           <div class="cr-t-card">
                               <div class="cr-t-photo" id="crMainPhoto"></div>
                               <div class="cr-t-body">
                                   <div class="cr-quote-mark" aria-hidden="true">&ldquo;</div>
                                   <p class="cr-t-quote" id="crMainQuote"></p>
                                   <div class="cr-t-name" id="crMainName"></div>
                                   <div class="cr-t-role" id="crMainRole"></div>
                               </div>
                           </div>

                           <?php if (count($employeeReviews) > 1): ?>
                           <div class="cr-peek" id="crPeekNext"></div>
                           <button type="button" class="cr-nav-btn" id="crNextBtn" aria-label="Next">
                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                           </button>
                           <?php endif; ?>
                       </div>

                       <?php if (count($employeeReviews) > 1): ?>
                       <div class="cr-dots" id="crDots"></div>
                       <?php endif; ?>
                   </div>
               </section>
               <!-- ======== END WHAT OUR PEOPLE SAY ======== -->

           </div>

           <script>
           (function() {
               var reviews = <?= json_encode($employeeReviews, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
               var current = 0;

               function initials(name) {
                   return (name || '?').trim().charAt(0).toUpperCase();
               }
               function avatarHTML(photo, name, size) {
                   if (photo) {
                       return '<img src="' + photo + '" alt="' + name.replace(/"/g, '&quot;') + '">';
                   }
                   return '<div class="cr-peek-fallback" style="' + (size ? 'font-size:' + size + 'px;' : '') + '">' + initials(name) + '</div>';
               }

               function render() {
                   var r = reviews[current];
                   var mainPhoto = document.getElementById('crMainPhoto');
                   mainPhoto.innerHTML = r.photo
                       ? '<img src="' + r.photo + '" alt="' + r.name.replace(/"/g, '&quot;') + '">'
                       : '<div class="cr-t-photo-fallback">' + initials(r.name) + '</div>';
                   document.getElementById('crMainQuote').textContent = r.quote;
                   document.getElementById('crMainName').textContent = r.name;
                   document.getElementById('crMainRole').textContent = r.role;

                   if (reviews.length > 1) {
                       var prevIdx = (current - 1 + reviews.length) % reviews.length;
                       var nextIdx = (current + 1) % reviews.length;
                       var peekPrev = document.getElementById('crPeekPrev');
                       var peekNext = document.getElementById('crPeekNext');
                       if (peekPrev) peekPrev.innerHTML = avatarHTML(reviews[prevIdx].photo, reviews[prevIdx].name, 20);
                       if (peekNext) peekNext.innerHTML = avatarHTML(reviews[nextIdx].photo, reviews[nextIdx].name, 20);

                       var dotsWrap = document.getElementById('crDots');
                       if (dotsWrap) {
                           dotsWrap.innerHTML = '';
                           reviews.forEach(function(_, i) {
                               var d = document.createElement('button');
                               d.type = 'button';
                               d.className = 'cr-dot' + (i === current ? ' active' : '');
                               d.setAttribute('aria-label', 'Go to review ' + (i + 1));
                               d.onclick = function() { current = i; render(); };
                               dotsWrap.appendChild(d);
                           });
                       }
                   }
               }

               var prevBtn = document.getElementById('crPrevBtn');
               var nextBtn = document.getElementById('crNextBtn');
               if (prevBtn) prevBtn.onclick = function() { current = (current - 1 + reviews.length) % reviews.length; render(); };
               if (nextBtn) nextBtn.onclick = function() { current = (current + 1) % reviews.length; render(); };

               render();
           })();
           </script>

           </main>

          <?php include('common/footer.php'); ?>
    </body>
</html>
