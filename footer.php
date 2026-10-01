<?php

$info = get_fields('option');

$general_setting = $info['general_setting'] ?? [];

$contact_address = $general_setting['contact_address'] ?? '';
$contact_number  = $general_setting['contact_number'] ?? '';
$contact_email   = $general_setting['contact_email'] ?? '';

?>

<footer class="site-footer">

    <div class="container">

        <!-- Footer Main -->
        <div class="footer-main pd-sm">

            <!-- CHANGED: align-items-start to align-items-center for vertical balance -->
            <div class="row align-items-center">

                <!-- Contact -->
                <div class="col-12 col-sm-4">
                    <div class="footer-contact">

                        <?php if ($contact_address) : ?>
                            <p class="contact-address">
                                <?php echo nl2br(esc_html($contact_address)); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($contact_number) : ?>
                            <p class="contact-phone">
                                Tel :
                                <?php
                                $phone_numbers = explode(',', $contact_number);
                                $phone_numbers = array_map('trim', $phone_numbers);
                                $phone_numbers = array_filter($phone_numbers);

                                foreach ($phone_numbers as $index => $phone) :
                                    $tel = preg_replace('/[^0-9+]/', '', $phone);
                                ?>
                                    <a href="tel:<?php echo esc_attr($tel); ?>">
                                        <?php echo esc_html($phone); ?>
                                    </a>

                                    <?php if ($index < count($phone_numbers) - 1) : ?>
                                        <span class="phone-separator">, </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($contact_email) : ?>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="mail">
                                <?php echo esc_html($contact_email); ?>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Logo / Social -->
                <div class="col-12 col-sm-4">
                    <!-- CHANGED: Removed text-center class to let CSS handle responsiveness -->
                    <div class="footer-brand">

                        <!-- Footer Logo -->
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo" aria-label="Footer Logo">
                            <img src="/app/uploads/2026/09/viber_image_2026-08-24_13-13-25-484-1.png" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        </a>

                        <!-- Social -->
                        <div class="footer-social">
                            <span>Follow Us:</span>
                            <a href="#" aria-label="Facebook">
                                <img src="/app/uploads/2026/09/ri_facebook-fill.svg" alt="Facebook">
                            </a>
                            <a href="#" aria-label="LinkedIn">
                                <img src="/app/uploads/2026/09/ri_linkedin-fill.svg" alt="LinkedIn">
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Footer Menu -->
                <div class="col-12 col-sm-4">
                    <div class="footer-nav">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'nav-menu',
                            'fallback_cb'    => false,
                        ]);
                        ?>
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">

                <!-- Copyright -->
                <div class="col-12 col-md-8">
                    <div class="copyright">
                        Copyright © <?php echo esc_html(date('Y')); ?>
                        Native Asian Dynamism. All Rights Reserved.
                    </div>
                </div>

                <!-- Created By -->
                <div class="col-12 col-md-4">
                    <div class="created-by">
                        <span>Created by</span>
                        <a href="https://digitaldots.com.mm/" target="_blank" rel="noopener noreferrer" aria-label="Digital Dots">
                            <img src="/app/uploads/2026/09/DD-LOGO-1-1.png" alt="Digital Dots">
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>