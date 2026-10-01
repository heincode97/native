<?php
add_action('after_setup_theme', 'mytheme_setup');
add_action('acf/init', 'add_acf_custom_blocks');

/* Register for Gutenberg Wide Images */
function mytheme_setup()
{
    add_theme_support('align-wide');
}

/* Register Custom Category Block for Gutenberg Editor */
function nptsb_block_category($categories, $post)
{
    return array_merge(
        array(
            array(
                'slug' => 'nptsb-blocks',
                'title' => __('Nay Pyi Taw Sibin Bank Blocks', 'nptsb-blocks'),
            ),
        ),
        $categories
    );
}
add_filter('block_categories', 'nptsb_block_category', 10, 2);

function add_acf_custom_blocks()
{
    acf_register_block(
        array(
            'name' => 'media-text',
            'title' => 'Media & Text Block',
            'render_template' => 'blocks/media-text.php',
            'category' => 'nptsb-blocks',
            'icon' => 'admin-comments',
        )
    );
}

?>