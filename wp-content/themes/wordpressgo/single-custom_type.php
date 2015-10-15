<?php
/*
 * Template single pour le CPT
 * http://codex.wordpress.org/Post_Type_Templates
*/
?>

<?php get_header(); ?>

<div class="container">

	<div class="row">

		<main id="main" class="col-md-12" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article">

					<header class="article-header">

						<h1 class="single-title custom-post-type-title"><?php the_title(); ?></h1>
						<p class="byline vcard"><?php
							printf( __( 'Posted <time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time> by <span class="author">%3$s</span> <span class="amp">&</span> filed under %4$s.', 'wordpressgotheme' ), get_the_time( 'Y-m-j' ), get_the_time(get_option('date_format')), get_the_author_link( get_the_author_meta( 'ID' ) ), get_the_term_list( $post->ID, 'custom_cat', ' ', ', ', '' ) );
						?></p>

					</header>

					<section class="entry-content cf">
						<?php
							// the content (pretty self explanatory huh)
							the_content();

							/*
							 * Link Pages is used in case you have posts that are set to break into
							 * multiple pages. You can remove this if you don't plan on doing that.
							 *
							 * Also, breaking content up into multiple pages is a horrible experience,
							 * so don't do it. While there are SOME edge cases where this is useful, it's
							 * mostly used for people to get more ad views. It's up to you but if you want
							 * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
							 *
							 * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
							 *
							*/
							wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wordpressgotheme' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
							) );
						?>
					</section> <!-- end article section -->

					<footer class="article-footer">
						<p class="tags"><?php echo get_the_term_list( get_the_ID(), 'custom_tag', '<span class="tags-title">' . __( 'Custom Tags:', 'wordpressgotheme' ) . '</span> ', ', ' ) ?></p>

					</footer>

					<?php comments_template(); ?>

				</article>

				<?php endwhile; ?>

				<?php else : ?>

						<article id="post-not-found" class="hentry cf">
							<header class="article-header">
								<h1><?php _e( 'Oops, Post Not Found!', 'wordpressgotheme' ); ?></h1>
							</header>
							<section class="entry-content">
								<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'wordpressgotheme' ); ?></p>
							</section>
							<footer class="article-footer">
								<p><?php _e( 'This is the error message in the single-custom_type.php template.', 'wordpressgotheme' ); ?></p>
							</footer>
						</article>

				<?php endif; ?>

			</main>

			<?php get_sidebar(); ?>

	</div>

</div>

<?php get_footer(); ?>
