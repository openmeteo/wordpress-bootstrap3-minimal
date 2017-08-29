<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-meta">
						<?php posted_on(); ?>
					</div><!-- .entry-meta -->

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:'), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

				</article>

				<hr>

				<!--
				<p class="categories text-muted">
					Categories: <?php the_category(', '); ?>
				</p>

				<hr>
				-->

				<nav id="nav-below" class="row navigation">
					<div class="col-xs-6"><?php previous_post_link( '%link', '&larr; %title', true ); ?></div>
					<div class="col-xs-6"><?php next_post_link( '%link', '%title &rarr;', true ); ?></div>
				</nav>

<?php endwhile; // end of the loop. ?>
