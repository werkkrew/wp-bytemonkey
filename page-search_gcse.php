<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package bytemonkey
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<header class="page-header">
				<h1 class="page-title">Search Results</h1>
			</header><!-- .page-header -->
<!-- Google CSE Code -->
<script>
  (function() {
    var cx = '004364479666620937412:d9zjyedvax4';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:searchresults-only></gcse:searchresults-only>
<!-- End Google CSE Code -->

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
