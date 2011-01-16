<?php /* Template Name: Home Page */ ?>
<?php get_header(); ?>
<div id="container">
	<div id="content" role="main">				

	<div class="clear"></div>

	<div class="container_16">
		<?php query_posts('page_id=102'); ?>
		<?php while (have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
	
	<div class="clear"></div>
	
	<div class="container_16" id="supportBox">
		<?php query_posts('page_id=18'); ?>
		<?php while (have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
        <div class="clear"></div>
	</div>

	

	<div class="container_16">
		<?php query_posts('page_id=116'); ?>
		<?php while (have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>

	<div class="clear"></div>

	<div class="container_16">
		<?php query_posts('page_id=118'); ?>
		<?php while (have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>

	<div class="clear"></div>
	
	</div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
