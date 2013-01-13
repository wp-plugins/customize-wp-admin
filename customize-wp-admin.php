<?php
/*
Plugin Name: Customize WP-admin
Plugin URI: http://www.arteck.mx/project/customize-wp-admin/
Description: Customize WP-admin lets you easily remove any menu or submenu from the admin sidebar, change the footer at wp-admin, and change the image and link of Wordpress on wp-login.
Author: Arteck
Version: 0.2.1
Author URI: http://www.arteck.mx

Copyright 2012  Arteck  (email : adrian.rangel@arteck.mx)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	register_activation_hook(__FILE__, 'cwa_add_defaults_fn');
	//add_action('admin_init', 'cwa_create_menu' );
	add_action('admin_menu', 'cwa_create_menu');
	load_plugin_textdomain('customize-wp-admin', false, dirname(plugin_basename(__FILE__)) . '/lang/');
	
	function cwa_my_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('my-upload', WP_PLUGIN_URL.'/customize-wp-admin/uploader.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('my-upload');
	}
	function cwa_my_admin_styles() {
		wp_enqueue_style('thickbox');
	}
	if (isset($_GET['page']) && $_GET['page'] == 'customize-wp-admin/customize-wp-admin.php') {
		add_action('admin_print_scripts', 'cwa_my_admin_scripts');
		add_action('admin_print_styles', 'cwa_my_admin_styles');
	}
	
	function cwa_create_menu() {
		//call register settings function
		add_action( 'admin_init', 'cwa_register_mysettings' );
		$tmp = get_option('cwa_options');
		if ($tmp['change_plugin_location']=='on' && $tmp['remove_settings']!='on'){
			//sub-level menu
			add_submenu_page( 'options-general.php', 'Customize WP-Admin', __('CWA Settings','customize-wp-admin'), 'administrator', __FILE__, 'cwa_settings_page');
		}else{
			if ($tmp['change_plugin_location']=='on' && $tmp['remove_settings']=='on'){
				add_action('admin_notices', 'cwa_error_change_location');
			}
			//create new top-level menu
			add_menu_page('Customize WP-Admin', __('CWA Settings','customize-wp-admin'), 'administrator', __FILE__, 'cwa_settings_page',plugins_url('/images/icon.png', __FILE__));
		}
	}
	/* Conditionally display notice */
	function cwa_error_change_location() {
    	/* Check that the user hasn't already clicked to ignore the message */
    	if ( ! get_user_meta( get_current_user_id(), '_cwa_ignore_change_error', true ) ) {
     	   echo '<div class="error"><p> ';
     	   _e( "Make sure the Remove Settings option is not enabled, before moving CWA Settings to Settings sub-menu.", 'customize-wp-admin' );
     	   echo '</p></div>';
    	}
	}
	

	
	function cwa_register_mysettings() {
		register_setting('cwa_options', 'cwa_options', 'cwa_options_validate' );
		add_settings_section('main_section', __('Main Settings','customize-wp-admin'), 'cwa_section_text_fn', __FILE__);
		add_settings_field('link_login', __('Link Login Page','customize-wp-admin'), 'cwa_setting_llp_fn', __FILE__, 'main_section');
		add_settings_field('upload_image', __('Image Login Page','customize-wp-admin'), 'cwa_setting_ilp_fn', __FILE__, 'main_section');
		add_settings_field('wp_footer', __('Footer Of Your WP Dashboard','customize-wp-admin'), 'cwa_setting_fwpa_fn', __FILE__, 'main_section');
		//add_settings_field('remove_adminbarback', __('Remove Admin-Bar from backend','customize-wp-admin'), 'cwa_setting_radminbarback_fn', __FILE__, 'main_section');
		add_settings_field('remove_adminbarfront', __('Remove Admin-Bar from frontend','customize-wp-admin'), 'cwa_setting_radminbarfront_fn', __FILE__, 'main_section');
		add_settings_field('change_plugin_location', __('Move CWA Settings from sidebar to Settings','customize-wp-admin'), 'cwa_setting_changepl_fn', __FILE__, 'main_section');

		add_settings_section('side_section', __('Wordpress Sidebar','customize-wp-admin'), 'cwa_section_sbtitle_fn', __FILE__);
		add_settings_field('remove_dashboard', __('Remove Dashboard','customize-wp-admin'), 'cwa_setting_rdashbaord_fn', __FILE__, 'side_section');
		add_settings_field('remove_posts', __('Remove Posts','customize-wp-admin'), 'cwa_setting_rposts_fn', __FILE__, 'side_section');
			add_settings_field('remove_poall', __('<span style="margin-left: 15px">> Remove All Posts</span>','customize-wp-admin'), 'cwa_setting_rpoallposts_fn', __FILE__, 'side_section');
			add_settings_field('remove_poaddnew', __('<span style="margin-left: 15px">> Remove Add New</span>','customize-wp-admin'), 'cwa_setting_rpoaddnew_fn', __FILE__, 'side_section');
			add_settings_field('remove_pocategories', __('<span style="margin-left: 15px">> Remove Categories</span>','customize-wp-admin'), 'cwa_setting_rpocategories_fn', __FILE__, 'side_section');
			add_settings_field('remove_potags', __('<span style="margin-left: 15px">> Remove Tags</span>','customize-wp-admin'), 'cwa_setting_rpotags_fn', __FILE__, 'side_section');
		add_settings_field('remove_media', __('Remove Media','customize-wp-admin'), 'cwa_setting_rmedia_fn', __FILE__, 'side_section');
			add_settings_field('remove_mlibrary', __('<span style="margin-left: 15px">> Remove Library</span>','customize-wp-admin'), 'cwa_setting_rmlibrary_fn', __FILE__, 'side_section');
			add_settings_field('remove_maddnew', __('<span style="margin-left: 15px">> Remove Add New</span>','customize-wp-admin'), 'cwa_setting_rmaddnew_fn', __FILE__, 'side_section');
		add_settings_field('remove_pages', __('Remove Pages','customize-wp-admin'), 'cwa_setting_rpages_fn', __FILE__, 'side_section');
			add_settings_field('remove_pgall', __('<span style="margin-left: 15px">> Remove All Pages</span>','customize-wp-admin'), 'cwa_setting_rpgallpages_fn', __FILE__, 'side_section');
			add_settings_field('remove_pgaddnew', __('<span style="margin-left: 15px">> Remove Add New</span>','customize-wp-admin'), 'cwa_setting_rpgaddnew_fn', __FILE__, 'side_section');
		add_settings_field('remove_comments', __('Remove Comments','customize-wp-admin'), 'cwa_setting_rcomments_fn', __FILE__, 'side_section');
		add_settings_field('remove_appearance', __('Remove Appearance','customize-wp-admin'), 'cwa_setting_rappearance_fn', __FILE__, 'side_section');
			add_settings_field('remove_apthemes', __('<span style="margin-left: 15px">> Remove Themes</span>','customize-wp-admin'), 'cwa_setting_rapthemes_fn', __FILE__, 'side_section');
			add_settings_field('remove_apwidgets', __('<span style="margin-left: 15px">> Remove Widgets</span>','customize-wp-admin'), 'cwa_setting_rapwidgets_fn', __FILE__, 'side_section');
			add_settings_field('remove_apmenus', __('<span style="margin-left: 15px">> Remove Menus</span>','customize-wp-admin'), 'cwa_setting_rapmenus_fn', __FILE__, 'side_section');
			add_settings_field('remove_apbackground', __('<span style="margin-left: 15px">> Remove Background</span>','customize-wp-admin'), 'cwa_setting_rapbackground_fn', __FILE__, 'side_section');
			add_settings_field('remove_apeditor', __('<span style="margin-left: 15px">> Remove Editor</span>','customize-wp-admin'), 'cwa_setting_rapeditor_fn', __FILE__, 'side_section');
		add_settings_field('remove_plugins', __('Remove Plugins','customize-wp-admin'), 'cwa_setting_rplugins_fn', __FILE__, 'side_section');
			add_settings_field('remove_plinstalled', __('<span style="margin-left: 15px">> Remove Installed Plugins</span>','customize-wp-admin'), 'cwa_setting_rplinstalled_fn', __FILE__, 'side_section');
			add_settings_field('remove_pladdnew', __('<span style="margin-left: 15px">> Remove Add New</span>','customize-wp-admin'), 'cwa_setting_rpladdnew_fn', __FILE__, 'side_section');
			add_settings_field('remove_pleditor', __('<span style="margin-left: 15px">> Remove Editor</span>','customize-wp-admin'), 'cwa_setting_rpleditor_fn', __FILE__, 'side_section');
		add_settings_field('remove_users', __('Remove Users','customize-wp-admin'), 'cwa_setting_rusers_fn', __FILE__, 'side_section');
			add_settings_field('remove_usall', __('<span style="margin-left: 15px">> Remove All Users</span>','customize-wp-admin'), 'cwa_setting_rusall_fn', __FILE__, 'side_section');
			add_settings_field('remove_usaddnew', __('<span style="margin-left: 15px">> Remove Add New</span>','customize-wp-admin'), 'cwa_setting_rusaddnew_fn', __FILE__, 'side_section');
			add_settings_field('remove_usyourp', __('<span style="margin-left: 15px">> Remove Your Profile</span>','customize-wp-admin'), 'cwa_setting_rusyourp_fn', __FILE__, 'side_section');
		add_settings_field('remove_tools', __('Remove Tools','customize-wp-admin'), 'cwa_setting_rtools_fn', __FILE__, 'side_section');
			add_settings_field('remove_toavaible', __('<span style="margin-left: 15px">> Remove Available Tools</span>','customize-wp-admin'), 'cwa_setting_rtoavaible_fn', __FILE__, 'side_section');		
			add_settings_field('remove_toimport', __('<span style="margin-left: 15px">> Remove Import</span>','customize-wp-admin'), 'cwa_setting_rtoimport_fn', __FILE__, 'side_section');		
			add_settings_field('remove_toexport', __('<span style="margin-left: 15px">> Remove Export</span>','customize-wp-admin'), 'cwa_setting_rtoexport_fn', __FILE__, 'side_section');		
		add_settings_field('remove_settings', __('Remove Settings','customize-wp-admin'), 'cwa_setting_rsettings_fn', __FILE__, 'side_section');
			add_settings_field('remove_stgeneral', __('<span style="margin-left: 15px">> Remove General</span>','customize-wp-admin'), 'cwa_setting_rstgeneral_fn', __FILE__, 'side_section');		
			add_settings_field('remove_stwriting', __('<span style="margin-left: 15px">> Remove Writing</span>','customize-wp-admin'), 'cwa_setting_rstwriting_fn', __FILE__, 'side_section');		
			add_settings_field('remove_streading', __('<span style="margin-left: 15px">> Remove Reading</span>','customize-wp-admin'), 'cwa_setting_rstreading_fn', __FILE__, 'side_section');		
			add_settings_field('remove_stdiscussion', __('<span style="margin-left: 15px">> Remove Discussion</span>','customize-wp-admin'), 'cwa_setting_rstdiscussion_fn', __FILE__, 'side_section');		
			add_settings_field('remove_stmedia', __('<span style="margin-left: 15px">> Remove Media</span>','customize-wp-admin'), 'cwa_setting_rstmedia_fn', __FILE__, 'side_section');		
			add_settings_field('remove_stpermalinks', __('<span style="margin-left: 15px">> Remove Permalinks</span>','customize-wp-admin'), 'cwa_setting_rstpermalinks_fn', __FILE__, 'side_section');		
		
		add_settings_section('dashboard_section', __('Wordpress Dashboard Widgets','customize-wp-admin'), 'cwa_section_wpwtitle_fn', __FILE__);
		add_settings_field('remove_dtoday', __('Remove Right Now','customize-wp-admin'), 'cwa_setting_rdtoday_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dlcomments', __('Remove Recent Comments','customize-wp-admin'), 'cwa_setting_rdlcomments_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dilinks', __('Remove Incoming Links','customize-wp-admin'), 'cwa_setting_rdilinks_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dplugins', __('Remove Plugins','customize-wp-admin'), 'cwa_setting_rdplugins_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dquickp', __('Remove QuickPress','customize-wp-admin'), 'cwa_setting_rdquickp_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_drecentd', __('Remove Recent Drafts','customize-wp-admin'), 'cwa_setting_rdrecentd_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dwpblog', __('Remove Wordpress Blog','customize-wp-admin'), 'cwa_setting_rdwpblog_fn', __FILE__, 'dashboard_section');
		add_settings_field('remove_dowpnews', __('Remove Other Wordpress News','customize-wp-admin'), 'cwa_setting_rdowpnews_fn', __FILE__, 'dashboard_section');
		
		add_settings_section('restore_section', __('Restore to defaults','customize-wp-admin'), 'cwa_section_rstitle_fn', __FILE__);
		add_settings_field('plugin_chk1', __('Restore Defaults Upon Reactivation?','customize-wp-admin'), 'cwa_setting_chk1_fn', __FILE__, 'restore_section');
	}
	function cwa_section_text_fn() {
		echo "<p>";
		_e('Change wp-login image,link, and wp-admin footer.','customize-wp-admin');
		echo "</p>";
	}
	function cwa_setting_llp_fn() {
		$options = get_option('cwa_options');
		echo "<input id='link_login' name='cwa_options[link_login]' size='40' type='text' value='{$options['link_login']}' />";
	}
	function cwa_setting_ilp_fn() {
		$options = get_option('cwa_options');
		echo "<input id='upload_image' name='cwa_options[upload_image]' size='40' type='text' value='{$options['upload_image']}' />";
		echo "<input class='button' id='upload_image_button' type='button' value='". __('Upload Image','customize-wp-admin') ."' />";
	}
	function cwa_setting_fwpa_fn() {
		$options = get_option('cwa_options');
		echo "<input id='wp_footer' name='cwa_options[wp_footer]' size='40' type='text' value='{$options['wp_footer']}' />";
	}
	
	/*function cwa_setting_radminbarback_fn(){
		$options = get_option('cwa_options');
		if($options['remove_adminbarback']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_adminbarback' name='cwa_options[remove_adminbarback]' type='checkbox' />";
	}*/
	function cwa_setting_radminbarfront_fn(){
		$options = get_option('cwa_options');
		if($options['remove_adminbarfront']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_adminbarfront' name='cwa_options[remove_adminbarfront]' type='checkbox' />";
	}
	function cwa_setting_changepl_fn(){
		$options = get_option('cwa_options');
		if($options['change_plugin_location']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='change_plugin_location' name='cwa_options[change_plugin_location]' type='checkbox' />";
	}
	
	function cwa_section_sbtitle_fn(){
		echo "<p>";
		_e('Select the options you would like to remove from the wp-admin sidebar','customize-wp-admin');
		echo "</p>";
	}

	function cwa_setting_rdashbaord_fn() {
		$options = get_option('cwa_options');
		if($options['remove_dashboard']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dashboard' name='cwa_options[remove_dashboard]' type='checkbox' />";
	}
	function cwa_setting_rpages_fn() {
		$options = get_option('cwa_options');
		if($options['remove_pages']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pages' name='cwa_options[remove_pages]' type='checkbox' />";
	}
	function cwa_setting_rposts_fn() {
		$options = get_option('cwa_options');
		if($options['remove_posts']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_posts' name='cwa_options[remove_posts]' type='checkbox' />";
	}
	function cwa_setting_rmedia_fn() {
		$options = get_option('cwa_options');
		if($options['remove_media']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_media' name='cwa_options[remove_media]' type='checkbox' />";
	}
	function cwa_setting_rcomments_fn() {
		$options = get_option('cwa_options');
		if($options['remove_comments']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_comments' name='cwa_options[remove_comments]' type='checkbox' />";
	}
	function cwa_setting_rappearance_fn() {
		$options = get_option('cwa_options');
		if($options['remove_appearance']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_appearance' name='cwa_options[remove_appearance]' type='checkbox' />";
	}
	function cwa_setting_rplugins_fn() {
		$options = get_option('cwa_options');
		if($options['remove_plugins']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_plugins' name='cwa_options[remove_plugins]' type='checkbox' />";
	}
	function cwa_setting_rusers_fn() {
		$options = get_option('cwa_options');
		if($options['remove_users']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_users' name='cwa_options[remove_users]' type='checkbox' />";
	}
	function cwa_setting_rtools_fn() {
		$options = get_option('cwa_options');
		if($options['remove_tools']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_tools' name='cwa_options[remove_tools]' type='checkbox' />";
	}
	function cwa_setting_rsettings_fn() {
		$options = get_option('cwa_options');
		if($options['remove_settings']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_settings' name='cwa_options[remove_settings]' type='checkbox' />";
	}
	
	function cwa_section_wpwtitle_fn(){
		echo "<p>";
		_e('Select the widgets you would like to remove from the WP Dashboard','customize-wp-admin');
		echo "</p>";
	}
	
	function cwa_setting_rdtoday_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dtoday']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dtoday' name='cwa_options[remove_dtoday]' type='checkbox' />";
	}
	function cwa_setting_rdlcomments_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dlcomments']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dlcomments' name='cwa_options[remove_dlcomments]' type='checkbox' />";
	}
	function cwa_setting_rdilinks_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dilinks']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dilinks' name='cwa_options[remove_dilinks]' type='checkbox' />";
	}
	function cwa_setting_rdplugins_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dplugins']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dplugins' name='cwa_options[remove_dplugins]' type='checkbox' />";
	}
	function cwa_setting_rdquickp_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dquickp']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dquickp' name='cwa_options[remove_dquickp]' type='checkbox' />";
	}
	function cwa_setting_rdrecentd_fn(){
		$options = get_option('cwa_options');
		if($options['remove_drecentd']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_drecentd' name='cwa_options[remove_drecentd]' type='checkbox' />";
	}
	function cwa_setting_rdwpblog_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dwpblog']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dwpblog' name='cwa_options[remove_dwpblog]' type='checkbox' />";
	}
	function cwa_setting_rdowpnews_fn(){
		$options = get_option('cwa_options');
		if($options['remove_dowpnews']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_dowpnews' name='cwa_options[remove_dowpnews]' type='checkbox' />";
	}	
	function cwa_setting_rpoallposts_fn(){
		$options = get_option('cwa_options');
		if($options['remove_poall']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_poall' name='cwa_options[remove_poall]' type='checkbox' />";
	}
	function cwa_setting_rpoaddnew_fn(){
		$options = get_option('cwa_options');
		if($options['remove_poaddnew']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_poaddnew' name='cwa_options[remove_poaddnew]' type='checkbox' />";
	}
	function cwa_setting_rpocategories_fn(){
		$options = get_option('cwa_options');
		if($options['remove_pocategories']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pocategories' name='cwa_options[remove_pocategories]' type='checkbox' />";
	}
	function cwa_setting_rpotags_fn(){
		$options = get_option('cwa_options');
		if($options['remove_potags']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_potags' name='cwa_options[remove_potags]' type='checkbox' />";
	}
	function cwa_setting_rmlibrary_fn(){
		$options = get_option('cwa_options');
		if($options['remove_mlibrary']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_mlibrary' name='cwa_options[remove_mlibrary]' type='checkbox' />";	
	}
	function cwa_setting_rmaddnew_fn(){
		$options = get_option('cwa_options');
		if($options['remove_maddnew']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_maddnew' name='cwa_options[remove_maddnew]' type='checkbox' />";	
	}
	function cwa_setting_rpgallpages_fn(){
		$options = get_option('cwa_options');
		if($options['remove_pgall']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pgall' name='cwa_options[remove_pgall]' type='checkbox' />";	
	}
	function cwa_setting_rpgaddnew_fn(){
		$options = get_option('cwa_options');
		if($options['remove_pgaddnew']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pgaddnew' name='cwa_options[remove_pgaddnew]' type='checkbox' />";	
	}
	function cwa_setting_rapthemes_fn(){
		$options = get_option('cwa_options');
		if($options['remove_apthemes']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_apthemes' name='cwa_options[remove_apthemes]' type='checkbox' />";	
	}
	function cwa_setting_rapwidgets_fn(){
		$options = get_option('cwa_options');
		if($options['remove_apwidgets']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_apwidgets' name='cwa_options[remove_apwidgets]' type='checkbox' />";	
	}
	function cwa_setting_rapmenus_fn(){
		$options = get_option('cwa_options');
		if($options['remove_apmenus']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_apmenus' name='cwa_options[remove_apmenus]' type='checkbox' />";	
	}
	function cwa_setting_rapbackground_fn(){
		$options = get_option('cwa_options');
		if($options['remove_apbackground']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_apbackground' name='cwa_options[remove_apbackground]' type='checkbox' />";	
	}
	function cwa_setting_rapeditor_fn(){
		$options = get_option('cwa_options');
		if($options['remove_apeditor']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_apeditor' name='cwa_options[remove_apeditor]' type='checkbox' />";	
	}
	function cwa_setting_rplinstalled_fn(){
		$options = get_option('cwa_options');
		if($options['remove_plinstalled']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_plinstalled' name='cwa_options[remove_plinstalled]' type='checkbox' />";	
	}
	function cwa_setting_rpladdnew_fn(){
		$options = get_option('cwa_options');
		if($options['remove_pladdnew']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pladdnew' name='cwa_options[remove_pladdnew]' type='checkbox' />";	
	}
	function cwa_setting_rpleditor_fn(){
		$options = get_option('cwa_options');
		if($options['remove_pleditor']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_pleditor' name='cwa_options[remove_pleditor]' type='checkbox' />";	
	}
	function cwa_setting_rusall_fn(){
		$options = get_option('cwa_options');
		if($options['remove_usall']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_usall' name='cwa_options[remove_usall]' type='checkbox' />";	
	}
	function cwa_setting_rusaddnew_fn(){
		$options = get_option('cwa_options');
		if($options['remove_usaddnew']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_usaddnew' name='cwa_options[remove_usaddnew]' type='checkbox' />";	
	}
	function cwa_setting_rusyourp_fn(){
		$options = get_option('cwa_options');
		if($options['remove_usyourp']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_usyourp' name='cwa_options[remove_usyourp]' type='checkbox' />";	
	}
	function cwa_setting_rtoavaible_fn(){
		$options = get_option('cwa_options');
		if($options['remove_toavaible']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_toavaible' name='cwa_options[remove_toavaible]' type='checkbox' />";	
	}
	function cwa_setting_rtoimport_fn(){
		$options = get_option('cwa_options');
		if($options['remove_toimport']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_toimport' name='cwa_options[remove_toimport]' type='checkbox' />";	
	}
	function cwa_setting_rtoexport_fn(){
		$options = get_option('cwa_options');
		if($options['remove_toexport']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_toexport' name='cwa_options[remove_toexport]' type='checkbox' />";	
	}
	function cwa_setting_rstgeneral_fn(){
		$options = get_option('cwa_options');
		if($options['remove_stgeneral']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_stgeneral' name='cwa_options[remove_stgeneral]' type='checkbox' />";	
	}
	function cwa_setting_rstwriting_fn(){
		$options = get_option('cwa_options');
		if($options['remove_stwriting']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_stwriting' name='cwa_options[remove_stwriting]' type='checkbox' />";	
	}
	function cwa_setting_rstreading_fn(){
		$options = get_option('cwa_options');
		if($options['remove_streading']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_streading' name='cwa_options[remove_streading]' type='checkbox' />";	
	}
	function cwa_setting_rstdiscussion_fn(){
		$options = get_option('cwa_options');
		if($options['remove_stdiscussion']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_stdiscussion' name='cwa_options[remove_stdiscussion]' type='checkbox' />";	
	}
	function cwa_setting_rstmedia_fn(){
		$options = get_option('cwa_options');
		if($options['remove_stmedia']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_stmedia' name='cwa_options[remove_stmedia]' type='checkbox' />";	
	}
	function cwa_setting_rstpermalinks_fn(){
		$options = get_option('cwa_options');
		if($options['remove_stpermalinks']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='remove_stpermalinks' name='cwa_options[remove_stpermalinks]' type='checkbox' />";	
	}
	
	function cwa_section_rstitle_fn(){
		echo "<p>";
		_e('Check the mark, disable and enable plugin to restore values to default','customize-wp-admin');
		echo "</p>";
	}
	
	function cwa_setting_chk1_fn() {
		$options = get_option('cwa_options');
		if($options['chkbox1']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='plugin_chk1' name='cwa_options[chkbox1]' type='checkbox' />";
	}
	
	function cwa_add_defaults_fn() {
		$tmp = get_option('cwa_options');
  		if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
			$arr = array("link_login" => "http://wordpress.org", "upload_image" => admin_url() . "images/wordpress-logo.png","wp_footer" => "Thank you for creating with <a href=\"http://wordpress.org\">WordPress</a>.", /*"remove_adminbarback" => "",*/ "remove_adminbarfront" => "", "change_plugin_location" => "", "remove_posts" => "", "remove_media" => "", "remove_pages" => "", "remove_comments" => "", "remove_appearance" => "", "remove_plugins" => "", "remove_users" => "", "remove_tools" => "", "remove_settings" => "", "remove_poall" => "", "remove_poaddnew" => "", "remove_pocategories" => "", "remove_potags" => "", "remove_mlibrary" => "", "remove_maddnew" => "", "remove_pgall" => "", "remove_pgaddnew" => "", "remove_apthemes" => "", "remove_apwidgets" => "", "remove_apmenus" => "", "remove_apbackground" => "", "remove_apeditor" => "", "remove_plinstalled" => "", "remove_pladdnew" => "", "remove_pleditor" => "", "remove_usall" => "", "remove_usaddnew" => "", "remove_usyourp" => "", "remove_toavaible" => "", "remove_toimport" => "", "remove_toexport" => "", "remove_stgeneral" => "", "remove_stwriting" => "", "remove_streading" => "", "remove_stdiscussion" => "", "remove_stmedia" => "", "remove_stpermalinks" => "", "dashboard_section" => "", "remove_dtoday" => "", "remove_dlcomments" => "", "remove_dilinks" => "", "remove_dplugins" => "", "chkbox2" => "on");
			update_option('cwa_options', $arr);
		}
	}

	function cwa_options_validate($input) {
		// Check our textbox option field contains no HTML tags - if so strip them out
		$input['link_login'] =  wp_filter_nohtml_kses($input['link_login']);	
		return $input; // return validated input
	}
	
	// Use your own external URL logo link
	function cwa_wpc_url_login(){
		$options = get_option('cwa_options');
		return $options['link_login']; // your URL here
	}
	add_filter('login_headerurl', 'cwa_wpc_url_login');
	
	// Custom WordPress Footer
	function cwa_change_footer_admin () {
		$options = get_option('cwa_options');
		echo $options['wp_footer'];
	}
	add_filter('admin_footer_text', 'cwa_change_footer_admin');
	//change logo
	add_action("login_head", "cwa_change_wpl_logo");
	function cwa_change_wpl_logo() {
		$options = get_option('cwa_options');
		echo "<style>
		#login h1 a {
			background: url('{$options['upload_image']}') no-repeat scroll center top transparent;
		}
		</style>";
	}
	//remove adminbar margin
	function cwa_remove_adminbar_margin() {  
    	$remove_adminbar_margin = '<style type="text/css">  
   	 	html { margin-top: -0px !important; }  
   	 	* html body { margin-top: -28px !important; }  
    	</style>';  
		echo $remove_adminbar_margin;  
	}  
	//remove admin bar 
	add_action('init','cwa_removeAdminBar');
    function cwa_removeAdminBar(){
    	$tmp = get_option('cwa_options');
		if ($tmp['remove_adminbarfront']=='on'){
			show_admin_bar(false);
			add_action( 'wp_head', 'cwa_remove_adminbar_margin' ); 
		}
  	}  
	
	add_action( "admin_menu", "cwa_remove_menu_pages" );
	function cwa_remove_menu_pages(){
		$tmp = get_option('cwa_options');
		if ($tmp['remove_dashboard']=='on')
			remove_menu_page("index.php");
		if ($tmp['remove_posts']=='on')
			remove_menu_page("edit.php");
		if ($tmp['remove_media']=='on')
			remove_menu_page("upload.php");
		if ($tmp['remove_pages']=='on')
			remove_menu_page("edit.php?post_type=page");
		if ($tmp['remove_comments']=='on')
			remove_menu_page("edit-comments.php");
		if ($tmp['remove_appearance']=='on')
			remove_menu_page("themes.php");
		if ($tmp['remove_plugins']=='on')
			remove_menu_page("plugins.php");
		if ($tmp['remove_users']=='on')
			remove_menu_page("users.php");
		if ($tmp['remove_tools']=='on')
			remove_menu_page("tools.php");
		if ($tmp['remove_settings']=='on')
			remove_menu_page("options-general.php");
		//submenus
		//posts
		if ($tmp['remove_poall']=='on')
			remove_submenu_page("edit.php","edit.php");
		if ($tmp['remove_poaddnew']=='on')
			remove_submenu_page("edit.php","post-new.php");
		if ($tmp['remove_pocategories']=='on')
			remove_submenu_page("edit.php","edit-tags.php?taxonomy=category");
		if ($tmp['remove_potags']=='on')
			remove_submenu_page("edit.php","edit-tags.php?taxonomy=post_tag");
		//media
		if ($tmp['remove_mlibrary']=='on')
			remove_submenu_page("upload.php","upload.php");
		if ($tmp['remove_maddnew']=='on')
			remove_submenu_page("upload.php","media-new.php");
		//pages
		if ($tmp['remove_pgall']=='on')
			remove_submenu_page("edit.php?post_type=page","edit.php?post_type=page");
		if ($tmp['remove_pgaddnew']=='on')
			remove_submenu_page("edit.php?post_type=page","post-new.php?post_type=page");
		//appearance
		if ($tmp['remove_apthemes']=='on')
			remove_submenu_page("themes.php","themes.php");
		if ($tmp['remove_apwidgets']=='on')
			remove_submenu_page("themes.php","widgets.php");
		if ($tmp['remove_apmenus']=='on')
			remove_submenu_page("themes.php","nav-menus.php");
		if ($tmp['remove_apbackground']=='on')
			remove_submenu_page("themes.php","themes.php?page=custom-background");
		if ($tmp['remove_apeditor']=='on')
			remove_submenu_page("themes.php","theme-editor.php");
		//plugins
		if ($tmp['remove_plinstalled']=='on')
			remove_submenu_page("plugins.php","plugins.php");
		if ($tmp['remove_pladdnew']=='on')
			remove_submenu_page("plugins.php","plugin-install.php");
		if ($tmp['remove_pleditor']=='on')
			remove_submenu_page("plugins.php","plugin-editor.php");
		//users
		if ($tmp['remove_usall']=='on')
			remove_submenu_page("users.php","users.php");
		if ($tmp['remove_usaddnew']=='on')
			remove_submenu_page("users.php","user-new.php");
		if ($tmp['remove_usyourp']=='on')
			remove_submenu_page("users.php","profile.php");
		//tools
		if ($tmp['remove_toavaible']=='on')
			remove_submenu_page("tools.php","tools.php");
		if ($tmp['remove_toimport']=='on')
			remove_submenu_page("tools.php","import.php");
		if ($tmp['remove_toexport']=='on')
			remove_submenu_page("tools.php","export.php");
		//settings
		if ($tmp['remove_stgeneral']=='on')
			remove_submenu_page("options-general.php","options-general.php");
		if ($tmp['remove_stwriting']=='on')
			remove_submenu_page("options-general.php","options-writing.php");
		if ($tmp['remove_streading']=='on')
			remove_submenu_page("options-general.php","options-reading.php");
		if ($tmp['remove_stdiscussion']=='on')
			remove_submenu_page("options-general.php","options-discussion.php");
		if ($tmp['remove_stmedia']=='on')
			remove_submenu_page("options-general.php","options-media.php");
		if ($tmp['remove_stpermalinks']=='on')
			remove_submenu_page("options-general.php","options-permalink.php");
	}
	
	add_action('wp_dashboard_setup', 'cwa_remove_widgets_dashboard');
	function cwa_remove_widgets_dashboard(){
		$tmp = get_option('cwa_options');
		if ($tmp['remove_dtoday']=='on')
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		if ($tmp['remove_dlcomments']=='on')	
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		if ($tmp['remove_dilinks']=='on')
			remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		if ($tmp['remove_dplugins']=='on')
			remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		
		if ($tmp['remove_dquickp']=='on')
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		if ($tmp['remove_drecentd']=='on')
			remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		if ($tmp['remove_dwpblog']=='on')
			remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		if ($tmp['remove_dowpnews']=='on')
			remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
	}
	
	
	function cwa_settings_page() { ?>
		<div class="wrap">
			<h2>Customize WP-Admin</h2>

			<form action="options.php" method="post">
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
				<?php settings_fields('cwa_options'); ?>
				<?php do_settings_sections(__FILE__); ?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
				</p>
			</form>
		</div>
	<?php } ?>