<?php
/*
Plugin Name: WP Super Settings
Plugin URI: http://wordpress.org/extend/plugins/wp-super-settings/
Description: WP Super Settings allows you to do various generally required settings in WordPress which are otherwise not available directly.
Version: 1.0.0
Author: Amandeep S. Patti
Author URI: http://www.aspatti.com
License: GPL2
*/

add_action('admin_init', 'wpss_init');
function wpss_init()
{
		register_setting('wpss_option_group','wpss_options');	
}

add_action('admin_menu', 'wpss_menu_init');
function wpss_menu_init() 
{
		add_options_page('WP Super Settings', 
										'WP Super Settings', 
										'manage_options', 
										'wpss_menu', 
										'wpss_options_page');
}

function wpss_options_page()
{
?>
<div class="wrap">
  <?php screen_icon( 'options-general' ); ?>
  <h2>WP Super Settings</h2>
  <form action="options.php" method="POST">
    <?php settings_fields('wpss_option_group'); ?>
    <?php $options = get_option('wpss_options'); ?>
    <table class="widefat"  width="300" >
      <thead>
        <tr>
          <th>Settings </th>
          <th>Option</th>
					<th width=50%>Description</th>
        </tr>
      </thead>
      <tfoot >
        <tr>
          <th>Settings </th>
          <th>Option</th>
					<th width=50%>Description</th>
        </tr>
      </tfoot>
      <tbody >
        <tr>
          <th scope="row">Revisions</th>
          <td><input type="radio" name="	" value="enable" <?php checked('enable', $options['save_revisions']); ?> />
            Enable<br>
            <input type="radio" name="wpss_options[save_revisions]" value="disable" <?php checked('disable', $options['save_revisions']); ?> />
            Disable </td>
						<td>Enable/Disable Post Revisions</td>
        </tr>
        <tr>
          <th>Auto Save</th>
          <td><input type="radio" name="wpss_options[auto_save]" value="enable" <?php checked('enable', $options['auto_save']); ?> />
            Enable<br>
            <input type="radio" name="wpss_options[auto_save]" value="disable" <?php checked('disable', $options['auto_save']); ?> />
            Disable </td>
						<td>Enable/Disable Post Auto Save</td>
        </tr>
        <tr>
          <th>Admin Bar</th>
          <td><input type="radio" name="wpss_options[admin_bar]" value="show" <?php checked('show', $options['admin_bar']); ?> />
            Show<br>
            <input type="radio" name="wpss_options[admin_bar]" value="hide" <?php checked('hide', $options['admin_bar']); ?> />
            Hide </td>
						<td>Show/Hide Admin Bar</td>
        </tr>
        <tr>
          <th>Shortcode - [Embed_Post]</th>
          <td><input type="radio" name="wpss_options[embed_post]" value="enable" <?php checked('enable', $options['embed_post']); ?> />
            Enable<br>
            <input type="radio" name="wpss_options[embed_post]" value="disable" <?php checked('disable', $options['embed_post']); ?> />
            Disable </td>
						<td>Enable/Disable Embed_Post shortcode</td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
  </form>
</div>
<?php
}

add_action( 'wp_print_scripts', 'wpss_auto_save_setting' );
function wpss_auto_save_setting() 
{
	$options = get_option('wpss_options');
	if( $options[auto_save] == "disable" )
	{
		wp_deregister_script('autosave');
	}
}

add_filter( 'show_admin_bar', 'wpss_admin_bar_setting' );
function wpss_admin_bar_setting($showvar) 
{
	global $show_admin_bar;
	$options = get_option('wpss_options');
	if( $options[admin_bar] == "hide" )
	{
		$show_admin_bar = false;
		return false;
	}
	else
	{
		return $showvar ;
	}
}

add_action('plugins_loaded', 'wpss_other_settings');

function wpss_other_settings()
{

	$options = get_option('wpss_options');
	
	if( $options['save_revisions'] == "disable" )
	{
		define('WP_POST_REVISIONS', false);
	}
	
	if( $options['embed_post'] == "enable" )
	{
		add_shortcode ('Embed_Post', 'embed_post');
	}

}

function embed_post($atts)
{
	extract(shortcode_atts(array('post_id' => 0), $atts));
	$post =  get_post($post_id);
	$post_title = $post->post_title;
	$post_content = do_shortcode($post->post_content);
	return "<b>$post_title</b><hr>$post_content";
}

?>