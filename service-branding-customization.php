<?php
require 'common/head.php';
$pagename = basename($_SERVER['SCRIPT_NAME']);
?>

<body class="appear-animate">
    <div class="page" id="top">
        <?php require 'common/nav.php'; ?>
        <main id="main">
            <!-- Service Hero Section -->
            <section class="hero-section" style="background: linear-gradient(135deg, #1B4B7C 0%, #254668 100%); padding: 80px 20px; text-align: center; color: white;">
                <div class="container">
                    <h1 style="font-size: 48px; font-weight: 700; margin-bottom: 20px;">Branding & Customization</h1>
                    <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto;">Transform your corporate gifts into powerful brand ambassadors with our expert branding and customization solutions</p>
                </div>
            </section>

            <!-- Service Overview -->
            <section style="padding: 60px 20px;">
                <div class="container">
                    <div style="max-width: 900px; margin: 0 auto;">
                        <h2 style="font-size: 36px; font-weight: 700; margin-bottom: 20px; color: #333;">What We Offer</h2>
                        <p style="font-size: 16px; line-height: 1.8; color: #666; margin-bottom: 30px;">
                            At SuperGifts, we understand that every brand is unique. Our comprehensive branding and customization services ensure that your corporate gifts perfectly reflect your brand identity and values. Whether it's embossing, engraving, printing, or complete design consultation, we handle everything in-house.
                        </p>

                        <h3 style="font-size: 24px; font-weight: 700; margin-top: 40px; margin-bottom: 20px; color: #333;">Our Branding Services Include:</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px;">
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">🎨 Logo Embossing</h4>
                                <p style="color: #666; margin: 0;">Professional embossing of your company logo on leather items, boxes, and premium products</p>
                            </div>
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">✏️ Engraving Services</h4>
                                <p style="color: #666; margin: 0;">Precision engraving on metal, wood, and glass items with personalized text and designs</p>
                            </div>
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">🖨️ Custom Printing</h4>
                                <p style="color: #666; margin: 0;">Full-color digital and offset printing for mugs, t-shirts, bags, and promotional items</p>
                            </div>
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">🎯 Design Consultation</h4>
                                <p style="color: #666; margin: 0;">Expert guidance on design, color schemes, and brand alignment for maximum impact</p>
                            </div>
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">📦 Packaging Branding</h4>
                                <p style="color: #666; margin: 0;">Custom branded boxes, pouches, and packaging to create an unforgettable unboxing experience</p>
                            </div>
                            <div style="padding: 25px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #2D6A4F;">
                                <h4 style="color: #2D6A4F; font-weight: 700; margin-bottom: 10px;">🌈 Co-Branding</h4>
                                <p style="color: #666; margin: 0;">Dual branding solutions for partnership programs and collaborative campaigns</p>
                            </div>
                        </div>

                        <h3 style="font-size: 24px; font-weight: 700; margin-top: 40px; margin-bottom: 20px; color: #333;">Why Choose Our Branding Services?</h3>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 12px 0; border-bottom: 1px solid #eee; color: #555;"><strong>✓ In-House Capabilities:</strong> All branding work is done in-house for quality control and quick turnaround</li>
                            <li style="padding: 12px 0; border-bottom: 1px solid #eee; color: #555;"><strong>✓ Expert Team:</strong> Experienced designers and craftspeople ensure flawless execution</li>
                            <li style="padding: 12px 0; border-bottom: 1px solid #eee; color: #555;"><strong>✓ Quality Guarantee:</strong> 100% satisfaction guarantee on all branding work</li>
                            <li style="padding: 12px 0; border-bottom: 1px solid #eee; color: #555;"><strong>✓ Quick Turnaround:</strong> Fast processing without compromising on quality</li>
                            <li style="padding: 12px 0; border-bottom: 1px solid #eee; color: #555;"><strong>✓ Bulk Capability:</strong> Equipped to handle large orders for corporate campaigns</li>
                            <li style="padding: 12px 0; color: #555;"><strong>✓ Custom Solutions:</strong> Flexible options to match your exact brand requirements</li>
                        </ul>

                        <div style="background: #f0f9f6; padding: 30px; border-radius: 10px; margin-top: 40px; text-align: center;">
                            <h3 style="color: #2D6A4F; margin-top: 0;">Ready to Brand Your Corporate Gifts?</h3>
                            <p style="color: #666; margin-bottom: 20px;">Contact our branding experts today for a personalized consultation and quote</p>
                            <a href="contact.php" style="display: inline-block; background: #2D6A4F; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: 600;">Get in Touch</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php require 'common/footer.php'; ?>
    </div>
    <!-- <?php require 'common/aiir_form.php'; ?> -->
</body>
</html>
