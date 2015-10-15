<?php get_header(); ?>

<div class="container">

	<div class="row">

		<main id="main" class="col-md-12" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

			<article id="post-not-found" class="hentry cf">

				<header class="article-header">

					<h1><?php _e( 'Epic 404 - Article Not Found', 'wordpressgotheme' ); ?></h1>

				</header>

				<section class="entry-content">

					<p><?php _e( 'The article you were looking for was not found, but maybe try looking again!', 'wordpressgotheme' ); ?></p>

				</section>

				<section class="search">

						<p><?php get_search_form(); ?></p>

				</section>

			</article>

		</main>
	</div>
</div>

<?php get_footer(); ?>
