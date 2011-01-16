<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

			<div id="content" class="container_16" role="main">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php $website = get_post_meta( get_the_ID(), SUPPORTGROUP_WEBSITE, true); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class('grid_9'); ?>>
					<h1 class="supportgroup-title"><?php the_title(); ?></h1>
                    <?php if ($website != ''): ?>
                        <span class="supportgroup-url"><a href="<?php echo $website; ?>"><?php echo $website; ?></a></span>
                    <?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

					<div class="entry-utility">
						<?php edit_post_link( __( 'Edit this Support Group', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-utility -->
				</div><!-- #post-## -->

                <div class="grid_6">
                    <div class="supportgroup-metadata">
                        <h3>About the Group</h3>
                        <?php lifesupport_get_supportgroup_fields(); ?>
                    </div>
                    <?php global $mappress; if ($mappress->shortcode_map() != ''): ?>
                        <div class="supportgroup-map">
                            <h3>Where They Meet</h3>
                            <?php global $mappress; echo $mappress->shortcode_map(); ?>
                        </div>
                    <?php endif; ?>
                </div>

				<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->

<?php get_footer(); ?>
