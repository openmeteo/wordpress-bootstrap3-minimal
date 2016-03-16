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
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
<?php
	wp_head();
	wp_enqueue_script('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array('jquery'));
?>

<div class="container-fluid" id="content">
	<nav class="navbar navbar-blue" role="navigation">
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
				<ul class="nav navbar-nav">
					<?php

					function show_menu($location) {
						$menu_locations = get_nav_menu_locations();
						if (!array_key_exists($location, $menu_locations)) {
							return;
						}
						$menu = wp_get_nav_menu_object($menu_locations[$location]);
						if (!$menu) {
							return;
						}
						echo '<li class="dropdown">';
						echo '<a class="dropdown-toggle" aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" href="#">';
						echo $menu->name;
						echo '<span class="caret"></span>';
						echo '</a>';
						wp_nav_menu(array(
							'container' => false,
							'menu_class' => 'dropdown-menu',
							'theme_location' => $location,
							'depth' => 2
						));
						echo '</li>';
					}

					show_menu("primary");
					show_menu("menu2");
					?>
				</ul>
				<form role="search" method="get" class="navbar-form navbar-right" action="<?php echo home_url( '/' ); ?>">
					<input type="search" class="form-control" placeholder="Search" value="" name="s" size="15" />
					<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
				</form>					
			</div>		
		</div>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
