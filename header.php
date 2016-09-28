<!DOCTYPE html>
<meta name="viewport" content="width=device-width">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s'), max( $paged, $page ) );

	?></title>
<?php
	$color_scheme = get_theme_mod("color_scheme");
	if (! $color_scheme) {
		$color_scheme = "red";
	}
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri() . "/style-" . $color_scheme . ".css"; ?>" />
<?php
	wp_head();
	wp_enqueue_script('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array('jquery'));
?>

<div class="container-fluid" id="content">
<?php
	switch ($color_scheme) {
		case "red": $navbar_style = "inverse"; break;
		default: $navbar_style = $color_scheme; break;
	}
?>
	<nav class='navbar <?php echo "navbar-" . $navbar_style; ?>' role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-details" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo home_url('/'); ?>"><?php echo bloginfo('name'); ?></a>
			</div>
			<div id="navbar-details" class='collapse navbar-collapse'>
				<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'container' => false,
						'menu_class' => 'nav navbar-nav',
						'walker' => new My_Walker_Nav_Menu));
				?>
				<?php
					if (get_theme_mod('show_search_box')) {
				?>
						<form role="search" method="get" class="navbar-form navbar-right" action="<?php echo home_url( '/' ); ?>">
							<input type="search" class="form-control" placeholder="Search" value="" name="s" size="15" />
							<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						</form>
				<?php
					}
				?>
			</div>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
