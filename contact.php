<?php
$pagename = basename($_SERVER['PHP_SELF']);

/* Contact hero image — admin-managed via Banner Management (slot 16).
   Falls back to the bundled image when nothing is uploaded. */
$contactHeroImg = 'images/birthdaygiftimage.jpg';
$cdb = @new mysqli("localhost", "superehc_aiir", "Aiir@8097000970", "superehc_sgipl");
if ($cdb && !$cdb->connect_error) {
    $cr = $cdb->query("SELECT file_path FROM banners WHERE slot=16 AND status=1 AND file_path<>'' LIMIT 1");
    if ($cr && ($crow = $cr->fetch_assoc()) && !empty($crow['file_path'])) {
        $contactHeroImg = $crow['file_path'];
    }
    $cdb->close();
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
           /* ============ CONTACT US PAGE — scoped styles ============ */
           .ct-wrap { background:#F4F5F8; color:#1f2340; overflow:hidden; }
           .ct-wrap a { text-decoration:none; }
           .ct-container { max-width:1200px; margin:0 auto; padding:0 24px; }

           /* Hero banner — full-width image, managed in admin (Banner slot 16) */
           .ct-hero-banner { width:100%; background:#EDEFF6; line-height:0; font-size:0; }
           .ct-hero-banner img { width:100%; height:auto; display:block; }

           /* Info card — soft brand-blue tint, distinct from the white form/map cards */
           .ct-info { position:relative; margin-top:44px; z-index:3; }
           .ct-info-card { background:#EEF2FB; border:1px solid #E2E9F8; border-radius:18px; box-shadow:0 24px 60px -30px rgba(25,26,69,.25); display:grid; grid-template-columns:1.3fr 1fr 1fr 1fr; }
           .ct-info-col { padding:34px 30px; border-right:1px solid #E2E9F8; }
           .ct-info-col:last-child { border-right:0; }
           .ct-info-ic { width:44px; height:44px; border-radius:50%; background:#DCE7FC; color:#2563EB; display:flex; align-items:center; justify-content:center; margin-bottom:16px; flex-shrink:0; }
           .ct-info-ic svg { width:20px; height:20px; }
           .ct-info-col h3 { font-size:16px; font-weight:800; color:#191A45; margin:0 0 10px; }
           .ct-info-col p { font-size:13.5px; line-height:1.7; color:#6b7091; margin:0; }
           .ct-info-col p a { color:#2563EB; font-weight:600; }

           /* Main: form + map — each in its own white card */
           .ct-main { padding:70px 0 84px; }
           .ct-main-grid { display:grid; grid-template-columns:1fr 1fr; gap:32px; align-items:start; }
           .ct-card { background:#fff; border:1px solid #e9ebf3; border-radius:18px; box-shadow:0 24px 60px -34px rgba(25,26,69,.25); padding:28px; }
           .ct-bar { width:52px; height:4px; border-radius:2px; background:#F5B301; margin-bottom:20px; }
           .ct-main h2 { font-family:'Poppins',sans-serif; font-size:34px; font-weight:800; color:#191A45; margin:0 0 12px; }
           .ct-main .ct-lead { font-size:15px; line-height:1.7; color:#5b607d; max-width:440px; margin:0 0 30px; }

           .ct-form .ct-row2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
           .ct-field { margin-bottom:20px; }
           .ct-field label { display:block; font-size:13px; font-weight:700; color:#3a3f5c; margin-bottom:8px; }
           .ct-field label span { color:#e4002b; }
           .ct-input-wrap { position:relative; }
           .ct-input-wrap > svg { position:absolute; left:16px; top:16px; width:18px; height:18px; color:#9aa0bd; pointer-events:none; }
           .ct-form input, .ct-form textarea { width:100%; border:1px solid #dcdfec; border-radius:12px; background:#fff; padding:14px 16px 14px 44px; font-size:14px; color:#1f2340; font-family:inherit; transition:border-color .2s, box-shadow .2s; }
           .ct-form textarea { min-height:132px; resize:vertical; padding-top:14px; }
           .ct-form input:focus, .ct-form textarea:focus { outline:0; border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,.12); }
           .ct-form input::placeholder, .ct-form textarea::placeholder { color:#a6abc4; }

           .ct-submit { width:100%; border:0; background:#1B255B; color:#fff; font-weight:700; font-size:15px; letter-spacing:.03em; padding:16px 24px; border-radius:12px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:12px; transition:background .2s, transform .2s; }
           .ct-submit:hover { background:#141c47; transform:translateY(-2px); }
           .ct-submit span { display:inline-flex; align-items:center; gap:12px; }
           .ct-submit svg { width:18px; height:18px; }
           .Aiir_form_loading { display:none; margin:14px auto 0; width:26px; height:26px; border:3px solid #dfe2ef; border-top-color:#1B255B; border-radius:50%; animation:ctSpin .7s linear infinite; }
           @keyframes ctSpin { to { transform:rotate(360deg); } }

           .ct-form-tip { display:flex; gap:10px; align-items:flex-start; margin-top:18px; font-size:12.5px; line-height:1.6; color:#8a8fa8; }
           .ct-form-tip svg { width:15px; height:15px; flex-shrink:0; margin-top:2px; color:#a6abc4; }
           .ct-form-tip a { color:#2563EB; text-decoration:underline; }
           #result { margin-top:16px; }
           #result .success { background:#e7f7ee; color:#137a3f; border:1px solid #b7e4c9; padding:12px 16px; border-radius:10px; font-size:14px; }
           #result .error { background:#fdecec; color:#b91c1c; border:1px solid #f3c0c0; padding:12px 16px; border-radius:10px; font-size:14px; }

           /* Map card */
           .ct-map-head { display:flex; align-items:center; gap:14px; margin-bottom:18px; }
           .ct-map-head .ct-info-ic { margin-bottom:0; background:#1B255B; color:#fff; }
           .ct-map-head h3 { font-size:18px; font-weight:800; color:#191A45; margin:0; }
           .ct-map-head p { font-size:13px; color:#8a8fa8; margin:2px 0 0; }
           .ct-map-frame { border-radius:12px; overflow:hidden; line-height:0; }
           .ct-map-frame iframe { width:100%; height:430px; border:0; display:block; }

           @media (max-width:991px) {
               .ct-info { margin-top:30px; }
               .ct-info-card { grid-template-columns:1fr 1fr; }
               .ct-info-col { padding:28px 24px; }
               .ct-info-col:nth-child(2) { border-right:0; }
               .ct-info-col:nth-child(1), .ct-info-col:nth-child(2) { border-bottom:1px solid #E2E9F8; }
               .ct-main { padding:56px 0 64px; }
               .ct-main-grid { grid-template-columns:1fr; gap:28px; }
           }

           @media (max-width:600px) {
               .ct-container { padding:0 16px; }
               .ct-info { margin-top:20px; }

               /* Info card — compact icon-beside-heading rows */
               .ct-info-card { grid-template-columns:1fr; border-radius:16px; }
               .ct-info-col {
                   padding:18px 16px;
                   border-right:0;
                   border-bottom:1px solid #E2E9F8;
                   display:grid;
                   grid-template-columns:38px 1fr;
                   gap:6px 14px;
               }
               .ct-info-col:last-child { border-bottom:0; }
               .ct-info-ic { grid-column:1; grid-row:1; width:38px; height:38px; margin:0; }
               .ct-info-ic svg { width:18px; height:18px; }
               .ct-info-col h3 { grid-column:2; grid-row:1; align-self:center; margin:0; font-size:15px; }
               .ct-info-col p  { grid-column:2; grid-row:2; margin:0; font-size:13px; line-height:1.65; word-break:break-word; }

               /* Main */
               .ct-main { padding:36px 0 48px; }
               .ct-main-grid { gap:18px; }
               .ct-card { padding:20px; border-radius:16px; }
               .ct-main h2 { font-size:22px; }
               .ct-main .ct-lead { font-size:13.5px; margin-bottom:22px; }
               .ct-bar { margin-bottom:14px; }
               .ct-form .ct-row2 { gap:12px; }
               .ct-form input, .ct-form textarea { padding:12px 14px 12px 40px; font-size:13.5px; }
               .ct-input-wrap > svg { left:13px; top:13px; width:16px; height:16px; }

               /* Map */
               .ct-map-head h3 { font-size:16px; }
               .ct-map-frame iframe { height:260px; }
           }

           /* Only stack Name/Email on genuinely tight phones */
           @media (max-width:380px) {
               .ct-form .ct-row2 { grid-template-columns:1fr; }
           }
           </style>

           <div class="ct-wrap">

               <!-- ======== HERO BANNER ======== -->
               <section class="ct-hero-banner">
                   <img src="<?= htmlspecialchars($contactHeroImg) ?>" alt="Contact Super Gifts">
               </section>

               <!-- ======== CONTACT INFO ======== -->
               <section class="ct-info">
                   <div class="ct-container">
                       <div class="ct-info-card">
                           <div class="ct-info-col">
                               <div class="ct-info-ic">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                               </div>
                               <h3>Registered Address</h3>
                               <p>SHED NO. 1, AND MANDAL, SURVEY NO. 42/ E2, OLD DEPOT LANE, next to HYDERABAD INDUSTRIES, near MANIYAR FARMS, Shamshabad, Ranga Reddy, Telangana &ndash; 501218</p>
                           </div>
                           <div class="ct-info-col">
                               <div class="ct-info-ic">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                               </div>
                               <h3>Hyderabad</h3>
                               <p>Mr. Saad Anwer<br><a href="tel:+918297915711">+91 82979 15711</a><br><a href="mailto:sales@supergifts.in">sales@supergifts.in</a></p>
                           </div>
                           <div class="ct-info-col">
                               <div class="ct-info-ic">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                               </div>
                               <h3>Mumbai</h3>
                               <p>Mr. Anil Mathai<br><a href="tel:+919967404027">+91 99674 04027</a><br><a href="mailto:anil@supergifts.in">anil@supergifts.in</a></p>
                           </div>
                           <div class="ct-info-col">
                               <div class="ct-info-ic">
                                   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                               </div>
                               <h3>Bengaluru</h3>
                               <p>Mr. Vishal Singh<br><a href="tel:+917022374746">+91 70223 74746</a><br><a href="mailto:vishal@supergifts.in">vishal@supergifts.in</a></p>
                           </div>
                       </div>
                   </div>
               </section>

               <!-- ======== FORM + MAP ======== -->
               <section class="ct-main">
                   <div class="ct-container">
                       <div class="ct-main-grid">

                           <!-- Form -->
                           <div class="ct-card">
                               <div class="ct-bar"></div>
                               <h2>Send Us a Message</h2>
                               <p class="ct-lead">We'd love to hear from you! Fill in the form below and our team will get back to you shortly.</p>

                               <form class="ct-form" id="contact_form">
                                   <div class="ct-row2">
                                       <div class="ct-field">
                                           <label for="name">Your Name <span>*</span></label>
                                           <div class="ct-input-wrap">
                                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                               <input type="text" name="name" id="name" placeholder="Enter your name" pattern=".{3,100}" required aria-required="true">
                                           </div>
                                       </div>
                                       <div class="ct-field">
                                           <label for="email">Email Address <span>*</span></label>
                                           <div class="ct-input-wrap">
                                               <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6"/></svg>
                                               <input type="email" name="email" id="email" placeholder="Enter your email" required aria-required="true">
                                           </div>
                                       </div>
                                   </div>

                                   <div class="ct-field">
                                       <label for="message">Message <span>*</span></label>
                                       <div class="ct-input-wrap">
                                           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                           <textarea name="message" id="message" placeholder="Enter your message"></textarea>
                                       </div>
                                   </div>

                                   <button type="button" class="ct-submit submit_btn" id="submit_btn" aria-controls="result">
                                       <span>Send Message
                                           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                       </span>
                                   </button>
                                   <div class="Aiir_form_loading" role="status" aria-label="Sending"></div>

                                   <div class="ct-form-tip">
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                       <span>All the fields are required. By sending the form you agree to the <a href="TermandCondition" target="_blank">Terms &amp; Conditions</a> and <a href="PrivacyPolicy" target="_blank">Privacy Policy</a>.</span>
                                   </div>

                                   <div id="result" role="region" aria-live="polite" aria-atomic="true"></div>
                               </form>
                           </div>

                           <!-- Map -->
                           <div class="ct-card">
                               <div class="ct-map-head">
                                   <div class="ct-info-ic">
                                       <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                   </div>
                                   <div>
                                       <h3>Our Location</h3>
                                       <p>Hyderabad, Telangana</p>
                                   </div>
                               </div>
                               <div class="ct-map-frame">
                                   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d133778.1110938268!2d78.38006787432819!3d17.40625144609129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb99daeaebd2c7%3A0xae93b78392bafbc2!2sHyderabad%2C%20Telangana!5e0!3m2!1sen!2sin!4v1707409615512!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                               </div>
                           </div>

                       </div>
                   </div>
               </section>

           </div>

       </main>

      <?php include('common/footer.php'); ?>
    </body>
</html>
