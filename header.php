<?php
global $THEME_OPTIONS;

$info = get_fields('option');

$general_setting = $info['general_setting'] ?? [];

// Both image fields return a URL string (ACF return_format = url)
$logo_url        = $general_setting['logo'] ?? '';
$second_logo_url = $general_setting['second_logo'] ?? '';

$logo_alt = get_bloginfo('name');
?>
<!doctype html>
<!--[if lt IE 7 ]>	<html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>		<html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>		<html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>		<html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="en" class="no-js">
<!--<![endif]-->

<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <?php if (file_exists(TEMPLATEPATH . '/favicon.png')) : ?>
        <link rel="shortcut icon"
            href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.png">
    <?php endif; ?>

    <script>
        window.siteHeaderLogo = {
            url: <?php echo wp_json_encode(esc_url($logo_url)); ?>,
            second: <?php echo wp_json_encode(esc_url($second_logo_url)); ?>,
            link: <?php echo wp_json_encode(esc_url(home_url('/'))); ?>,
            alt: <?php echo wp_json_encode($logo_alt); ?>
        };
    </script>

    <?php wp_head(); ?>
</head>

<?php $body_classes = join(' ', get_body_class()); ?>

<body class="<?php echo esc_attr($body_classes); ?>">

    <header class="site-header">
        <div class="container">
            <div class="site-header__inner">

                <div class="site-logo<?php echo !empty($second_logo_url) ? ' has-second-logo' : ''; ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>">

                        <?php if (!empty($logo_url)) : ?>
                            <img class="site-logo__main"
                                src="<?php echo esc_url($logo_url); ?>"
                                alt="<?php echo esc_attr($logo_alt); ?>"
                                width="260"
                                height="70">
                        <?php endif; ?>

                        <?php if (!empty($second_logo_url)) : ?>
                            <img class="site-logo__second"
                                src="<?php echo esc_url($second_logo_url); ?>"
                                alt="<?php echo esc_attr($logo_alt); ?>"
                                width="260"
                                height="70">
                        <?php endif; ?>

                    </a>
                </div>

                <nav class="main-navigation">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'main',
                        'container' => 'div',
                        'container_class' => 'stellarnav mobile',
                        'menu_class' => '',
                    ]);
                    ?>
                </nav>

            </div>
        </div>
    </header>

    <?php if (!is_404() && !is_search() && !is_page(16) && !is_front_page()) { ?>
        <?php get_template_part("templates/global/breadcrumb"); ?>
    <?php } ?>