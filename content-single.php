<?php
/**
 * @package bytemonkey
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail( 'bytemonkey-featured', array( 'class' => 'single-featured' )); ?>
	<div class="post-inner-content">
		<header class="entry-header page-header">

			<h1 class="entry-title "><?php the_title(); ?></h1>

			<div class="entry-meta">
				<?php bytemonkey_posted_on(); ?>

				<?php
					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( esc_html__( ', ', 'bytemonkey' ) );
					if ( $categories_list && bytemonkey_categorized_blog() ) :
				?>
				<span class="cat-links"><i class="fa fa-folder-open-o"></i>
					<?php printf( esc_html__( ' %1$s', 'bytemonkey' ), $categories_list ); ?>
				</span>
				<?php endif; // End if categories ?>
				<?php edit_post_link( esc_html__( 'Edit', 'bytemonkey' ), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>' ); ?>

			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before'            => '<div class="page-links">'.esc_html__( 'Pages:', 'bytemonkey' ),
					'after'             => '</div>',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'pagelink'          => '%',
					'echo'              => 1
	       		) );
	    	?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">

	    	<?php if(has_tag()) : ?>
	      <!-- tags -->
	      <div class="tagcloud">

	          <?php
	              $tags = get_the_tags(get_the_ID());
	              foreach($tags as $tag){
	                  echo '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a> ';
	              } ?>

	      </div>
	      <!-- end tags -->
	      <?php endif; ?>

		</footer><!-- .entry-meta -->
	</div>
	
	<?php if (get_the_author_meta('description') && 
			  (get_theme_mod('bytemonkey_author_block', 1) == 1) || 
			  function_exists( 'wpsabox_author_box' )) :  ?>
	<div class="blog-item-wrap"></div>
	<div class="post-inner-content secondary-content-box">
		<?php if ( function_exists( 'wpsabox_author_box' )) {
				echo wpsabox_author_box(); 
			} else {
		?>
		<!-- author bio -->
		<div class="author-bio content-box-inner">

			<!-- avatar -->
			<div class="avatar">
				<?php echo get_avatar(get_the_author_meta('ID') , '60'); ?>
			</div>
			<!-- end avatar -->

			<!-- user bio -->
			<div class="author-bio-content">
				<h4 class="author-name"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_the_author_meta('display_name'); ?></a></h4>
				<p class="author-description">
					<?php echo get_the_author_meta('description'); ?>
				</p>
			</div><!-- end .author-bio-content -->
		</div><!-- end .author-bio  -->
		<?php } ?>

		</div>
	<?php endif; ?>

</article><!-- #post-## -->
