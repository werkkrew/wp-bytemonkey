<?php
/**
 * bytemonkey Meta Boxes
 *
 */

add_action('add_meta_boxes', 'bytemonkey_add_custom_box');
/**
 * Add Meta Boxes.
 *
 * Add Meta box in page and post post types.
 */
function bytemonkey_add_custom_box()
{
    add_meta_box('siderbar-layout', //Unique ID
        __('Custom Page Layout', 'bytemonkey'), //Title
        'bytemonkey_sidebar_layout', //Callback function
        'page', //show metabox in pages
		'side'
		);
    add_meta_box('siderbar-layout', //Unique ID
        __('Custom Post Layout', 'bytemonkey'), //Title
        'bytemonkey_sidebar_layout', //Callback function
        'post', //show metabox in posts
        'side'
        );
}

/**
 * Displays metabox to for sidebar layout
 */
function bytemonkey_sidebar_layout()
{
    global $post;
	$site_layout = bytemonkey_get_layout_options();
    // Use nonce for verification
    wp_nonce_field(basename(__FILE__), 'custom_meta_box_nonce'); ?>

    <table id="sidebar-metabox" class="form-table" width="100%">
        <tbody>
            <tr>
                <label class="description"><?php
                    $layout = get_post_meta($post->ID, 'site_layout', true);?>
                    <select name="site_layout" id="site_layout">
                        <option value="">Default</option><?php
                        foreach( $site_layout as $key=>$val ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $layout, $key ); ?> ><?php echo $val; ?></option><?php
                        }?>
                    </select>
                </label>
            </tr>
        </tbody>
    </table><?php
}

/****************************************************************************************/


add_action('save_post', 'bytemonkey_save_custom_meta');
/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function bytemonkey_save_custom_meta($post_id)
{
    global $post;
	$site_layout = bytemonkey_get_layout_options();

    // Verify the nonce before proceeding.
    if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return;

    // Stop WP from clearing custom fields on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    if ( $_POST['site_layout'] ) {
        update_post_meta($post_id, 'site_layout', $_POST['site_layout']);
    } else{
        delete_post_meta($post_id, 'site_layout');
    }
}