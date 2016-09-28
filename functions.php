<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/** Tell WordPress to run bootstrapthreeminimal_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'bootstrapthreeminimal_setup' );

if ( ! function_exists( 'bootstrapthreeminimal_setup' ) ) {
		function bootstrapthreeminimal_setup() {
			// Make theme available for translation
			// Translations can be filed in the /languages/ directory
			load_theme_textdomain( 'bootstrapthreeminimal', get_template_directory() . '/languages' );

			$locale = get_locale();
			$locale_file = get_template_directory() . "/languages/$locale.php";
			if ( is_readable( $locale_file ) )
				require_once( $locale_file );

			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array(
				'primary' => __( 'Primary Navigation'),
			) );
		}
}

/**
 * Sets the post excerpt length to 40 characters.
 */
function bootstrapthreeminimal_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'bootstrapthreeminimal_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function bootstrapthreeminimal_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>') . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and bootstrapthreeminimal_continue_reading_link().
 */
function bootstrapthreeminimal_auto_excerpt_more( $more ) {
	return ' &hellip;' . bootstrapthreeminimal_continue_reading_link();
}
add_filter( 'excerpt_more', 'bootstrapthreeminimal_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 */
function bootstrapthreeminimal_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= bootstrapthreeminimal_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'bootstrapthreeminimal_custom_excerpt_more' );


if ( ! function_exists( 'posted_on' ) ) {
  /**
   * Prints HTML with meta information for the current post-date/time and author.
   */
  function posted_on() {
    printf('<p><span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>, <a href="%4$s" title="%5$s" rel="bookmark"><span class="entry-date">%6$s</span></a></p>',
        get_author_posts_url( get_the_author_meta( 'ID' ) ),
        esc_attr( sprintf( __( 'View all posts by %s'), get_the_author() ) ),
        get_the_author(),
        get_permalink(),
        esc_attr( get_the_time('c') ),
        get_the_date('j F Y')
    );
  }
}

if (! function_exists('theme_options')) {
	function theme_options($wp_customize) {

        /* Color scheme */
		$wp_customize->add_setting('color_scheme', array('default' => 'red'));
		$wp_customize->add_control('color_scheme',
			array(
				'label' => __('Color scheme', 'mytheme'),
				'section' => 'colors',
				'settings' => 'color_scheme',
				'type' => 'select',
				'choices' => array(
					'default' => 'Default',
					'red' => 'Red',
				),
				'priority' => 10,
			)
		);

		/* Option to show search box or not */
		$wp_customize->add_section('nav',
			array(
				'title' => __('Navigation', 'mytheme'),
				'priority' => 100,
				'capability' => 'edit_theme_options',
				'description' => __('Navigation', 'mytheme'),
			)
		);
		$wp_customize->add_setting('show_search_box', array('default' => true));
		$wp_customize->add_control('show_search_box',
			array(
				'label' => __('Show search box on navigation bar', 'mytheme'),
				'section' => 'nav',
				'settings' => 'show_search_box',
				'type' => 'checkbox',
				'priority' => 20,
			)
		);

	}
}
add_action('customize_register', 'theme_options');


/* Navigation bar
 *
 * We need to make some changes to the way wp_nav_menu renders a menu
 * by default, in order to make it as bootstrap wants it. First, if a
 * menu item has a submenu, it needs to have the "dropdown" class;
 * this is done by a 'nav_menu_css_class' filter. Second, such a menu
 * item must have a downwards pointing arrow; this is done by a
 * 'nav_menu_item_args' filter. Third, such a menu item must have
 * several attributes set on its "a" element, which is done by a
 * 'nav_menu_link_attributes' filter. Finally, the "ul" wrapper for
 * the submenu must have the "dropdown-menu" class; this is done by
 * subclassing Walker_Nav_Menu and redefining a function in the
 * subclass. When wp_nav_menu is called in header.php, it specifies
 * that walker subclass.
 */

function menu_add_class_for_dropdown( $classes, $item, $args, $depth ) {
	if (in_array('menu-item-has-children', $classes)) {
		$classes['dropdown'] = 'dropdown';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'menu_add_class_for_dropdown', 10, 4 );


function menu_add_caret_to_dropdown( $args, $item, $depth ) {
	if (in_array('menu-item-has-children', $item->classes)) {
		$args->link_after = '<span class="caret"></span>';
	} else {
		$args->link_after = '';
	}
	return $args;
}
add_filter( 'nav_menu_item_args', 'menu_add_caret_to_dropdown', 10, 3 );


function menu_set_link_attributes_for_dropdown( $atts, $item, $args ) {
	if (in_array('menu-item-has-children', $item->classes)) {
		$atts['aria-expanded'] = 'false';
		$atts['aria-haspopup'] = 'true';
		$atts['role'] = 'button';
		$atts['data-toggle'] = 'dropdown';
		$atts['href'] = '#';
		$item->classes[] = 'dropdown-toggle';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'menu_set_link_attributes_for_dropdown', 10, 3 );


class My_Walker_Nav_Menu extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
	}
}

/* End navigation bar */
