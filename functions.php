<?php
define('TEMPLATE_URL', get_stylesheet_directory_uri());
define('ASSET_URL', TEMPLATE_URL . '/assets/');
define('ASSET_VERSION', time());
define('IS_SCSS_COMPILE', true);

define('PROJECT_PG', 15);
// Include required files
$includes = [
	'includes/aq_resizer.php',
	'includes/theme-options.php',
	'includes/customize-dashboard.php',
	'vendor/autoload.php',
	'includes/extra-functions.php',
	'includes/shortcode.php',
];

foreach ($includes as $file) {
	$file_path = __DIR__ . '/' . $file;
	if (file_exists($file_path)) {
		require_once $file_path;
	}
}

/**
 * Compile SCSS files if enabled.
 */

use ScssPhp\ScssPhp\Compiler;

if (defined('IS_SCSS_COMPILE') && IS_SCSS_COMPILE && class_exists(Compiler::class)) {
	$compiler = new Compiler();
	$compiler->setImportPaths(__DIR__ . '/assets/scss/');
	$compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
	$cssOut = $compiler->compileString('@import "z.scss";')->getCss();
	file_put_contents(__DIR__ . '/assets/css/combine.min.css', $cssOut);
}

function the_template_url()
{
	echo TEMPLATE_URL;
}

if (! is_admin()) {
	add_action('init', 'init_scripts', 10);
}
/**
 * Deregister unnecessary scripts.
 */
function init_scripts()
{
	wp_deregister_script('wp-embed');
	wp_deregister_script('jquery');
	wp_deregister_script('comment-reply');
}

add_action('wp_enqueue_scripts', 'add_scripts', 10);
/**
 * Enqueue scripts and styles.
 */
function add_scripts()
{
	$js_path = ASSET_URL . 'js';
	$css_path = ASSET_URL . 'css';

	$js_libs = [
		['jquery', $js_path . '/jquery.js', null, null, false],
		['bootstrap', $js_path . '/bootstrap.min.js', ['jquery'], null, true],
		['stellarnav', $js_path . '/stellarnav.min.js', ['jquery'], null, true],
		['script', $js_path . '/script.js', ['jquery'], ASSET_VERSION, true],
	];

	$css_libs = [
		['font-awesome-7', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css', [], null, 'screen' ],
		['bootstrap', $css_path . '/bootstrap.min.css', [], null, 'screen'],
		['font-awesome', $css_path . '/font-awesome.min.css', [], null, 'screen'],
		['stellarnav', $css_path . '/stellarnav.min.css', [], null, 'screen'],
		['style', TEMPLATE_URL . '/style.css', [], ASSET_VERSION, 'all'],
	];

	foreach ($js_libs as $lib) {
		wp_enqueue_script($lib[0], $lib[1], $lib[2], $lib[3], $lib[4]);
	}

	foreach ($css_libs as $lib) {
		wp_enqueue_style($lib[0], $lib[1], $lib[2], $lib[3], $lib[4]);
	}

	wp_localize_script('script', 'siteSettings', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('site_nonce'),
	]);
}

/**
 * Add theme support.
 */
function mytheme_setup()
{
	add_theme_support('automatic-feed-links');
	add_theme_support('nav-menus');
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
	add_post_type_support('page', 'excerpt');

	register_nav_menus([
		'main' => 'Main Menu',
		'footer' => 'Footer Menu',
		'helpful' => 'Helpful Info',
		'privacy' => 'Privacy Info',
	]);
}

add_action('after_setup_theme', 'mytheme_setup');

function custom_tab_shortcode() {

    ob_start();

    get_template_part( 'partial/custom-tab' );

    return ob_get_clean();
}
add_shortcode( 'custom-tab', 'custom_tab_shortcode' );

/**
 * Register sidebars.
 */
if (function_exists('register_sidebar')) {
	register_sidebar(
		array(
			'name' => __('Main - Sidebar'),
			'id' => 'main-sidebar-widget-area',
			'description' => 'Widgets in this area will be shown on the right sidebar of default page',
			'before_widget' => '<aside class="widget">',
			'after_widget' => '</aside>',
			'before_title' => '',
			'after_title' => '',
		)
	);
}



/**
 * Custom comment formatting.
 */
function theme_comments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
?>
	<li>
		<article <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<header class="comment-author vcard">
				<?php echo get_avatar($comment, $size = '48', $default = '<path_to_url>'); ?>
				<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
				<time><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
						<?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?>
					</a></time>
				<?php edit_comment_link(__('(Edit)'), '  ', '') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<em>
					<?php _e('Your comment is awaiting moderation.') ?>
				</em>
				<br />
			<?php endif; ?>

			<?php comment_text() ?>

			<nav>
				<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</nav>
		</article>
		<!-- </li> is added by wordpress automatically -->
	<?php
}

/*
|--------------------------------------
|   Map_via_acf
|--------------------------------------
*/
function my_acf_init()
{
	if (function_exists('acf_update_setting')) {
		acf_update_setting('google_api_key', 'AIzaSyC44n4EJxputPRoWzorOaszqW-dFoVN8UE');
	}
}

add_action('acf/init', 'my_acf_init');

/**
 * Generate contact information links.
 */
function contact_description($input_info = null, $attribute_name = null)
{
	$explode_info = explode(',', $input_info);
	$output = '';

	foreach ($explode_info as $index => $info) {
		$output .= sprintf(
			'<a href="%s:%s"><span>%s</span></a>',
			esc_attr($attribute_name),
			esc_attr(trim($info)),
			esc_html(trim($info))
		);

		if ($index < count($explode_info) - 1) {
			$output .= ', ';
		}
	}

	echo $output;
}
