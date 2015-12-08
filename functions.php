<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run bootstrapthreeminimal_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'bootstrapthreeminimal_setup' );

if ( ! function_exists( 'bootstrapthreeminimal_setup' ) ):
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
endif;

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


if ( ! function_exists( 'posted_on' ) ) :
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
endif;

/* Add some nav menus for the navbar */
if (! function_exists('register_custom_menus')) :
	function register_custom_menus() {
		register_nav_menus(
			array(
				'menu2' => __('Second Menu'),
				'menu3' => __('Third Menu'),
			)
		);
	}
endif;
add_action('init', 'register_custom_menus');
