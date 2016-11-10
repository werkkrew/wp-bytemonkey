<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package bytemonkey
 */
?>
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .site-content -->

	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>

		<footer id="colophon" class="site-footer" >
			<div class="site-info container">
				<div class="row">
					<?php if( get_theme_mod('footer_social_icons', 0) ) bytemonkey_social_icons(); ?>
					<nav class="col-md-6">
						<?php bytemonkey_footer_links(); ?>
					</nav>
					<div class="copyright col-md-6">
						<?php echo get_theme_mod( 'footer_custom_text' ); ?>
						<?php bytemonkey_footer_info(); ?>
					</div>
				</div>
			</div><!-- .site-info -->
			<div class="scroll-to-top"><i class="fa fa-angle-up"></i></div><!-- .scroll-to-top -->
		</footer><!-- #colophon -->
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>