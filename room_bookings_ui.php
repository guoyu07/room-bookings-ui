<?php
/*
Plugin Name: Room Bookings UI
Plugin URI: https://github.com/trevorprinn/room-bookings-ui
Description: Wordpress UI for the Room Bookings application
Version: 1.0
Author: Trevor Prinn
Author URI: https://github.com/trevorprinn
License: GPL2
*/
/*
Copyright 2016  Trevor Prinn  (email : trev@babbacom.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('Room_Bookings_UI'))
{
    class Room_Bookings_UI
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
				add_action('admin_init', [&$this, 'admin_init']);
				add_action('admin_menu', [&$this, 'add_menu']);

				add_shortcode('bookings', [&$this, 'sc_bookings']);
				add_shortcode('bookings_cal', [&$this, 'sc_bookings_cal']);
        } 
    
        /**
         * Activate the plugin
         */
        public static function activate()
        {
        } 
    
        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
        		remove_shortcode('bookings');
        		remove_shortcode('bookings_cal');
        } 
        
        public function admin_init() {
        		$this->init_settings();
        }
        
       public function init_settings() {
			register_setting('room_bookings_ui-group', 'api_url');
    		register_setting('room_bookings_ui-group', 'username');
    		register_setting('room_bookings_ui-group', 'password');
       }
       
       public function add_menu() {
       	add_options_page('Room Bookings UI Settings', 'Room Bookings UI', 
       		'manage_options', 'room_bookings_ui', array(&$this, 'plugin_settings_page'));
       }
       
       public function plugin_settings_page() {
       	 if(!current_user_can('manage_options')) {
		        wp_die(__('You do not have sufficient permissions to access this page.'));
		    }
		    
		    include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
       }
       
       function sc_bookings() {
			 ob_start(); 
			 include('bookings_shortcode.php');
			 return ob_get_clean();
		 }
			
		 function sc_bookings_cal() {
			ob_start();
			include('bookings_cal_shortcode.php');
			return ob_get_clean();
		 }

    }
} 

if (class_exists('Room_Bookings_UI')) {
	register_activation_hook(__FILE__, ['Room_Bookings_UI', 'activate']);
	register_deactivation_hook(__FILE__, ['Room_Bookings_UI', 'deactivate']);
	
	$room_bookings_ui = new Room_Bookings_UI();
	
	// Add a link to the settings page onto the plugin page
	if(isset($room_bookings_ui))
	{
	    // Add the settings link to the plugins page
	    function room_bookings_ui_plugin_settings_link($links)
	    { 
	        $settings_link = '<a href="options-general.php?page=room_bookings_ui">Settings</a>'; 
	        array_unshift($links, $settings_link); 
	        return $links; 
	    }
	
	    $plugin = plugin_basename(__FILE__); 
	    add_filter("plugin_action_links_$plugin", 'room_bookings_ui_plugin_settings_link');
	}
}

