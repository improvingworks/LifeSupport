<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

hi
		<div id="container">
			<div id="content" role="main">				
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

					<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

				<?php endwhile; endif; ?>
			
			<div class="container_16">
				get_post(18)->post_content;
			</div>

			<div class="container_16">
				get_post();
			</div>
			
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
