<?php
/**
 * The template for displaying search forms in bytemonkey
 *
 * @package bytemonkey
 */
?>

<form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div class="input-group">
  	<label class="screen-reader-text" ><?php _e( 'Search for:', 'bytemonkey' ); ?></label>
    <input type="text" class="form-control search-query" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'bytemonkey' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'bytemonkey' ); ?>" />
    <span class="input-group-btn">
      <button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'bytemonkey' ); ?>"><span class="glyphicon glyphicon-search"></span></button>
    </span>
  </div>
</form>