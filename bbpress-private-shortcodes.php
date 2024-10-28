<?php
/*
Plugin Name: BBpress Private Shortcodes
Plugin URL: http://remicorson.com/bbpress-private-shortcodes
Description: A simple plugin that allows BBpress users to hide parts of their message using shortcodes
Version: 0.1
Author: Remi Corson
Author URI: http://remicorson.com
Contributors: corsonr
Text Domain: rc_bbpps
Domain Path: languages
*/

class BBP_private_shortcodes {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
	
		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// register css files
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		
		// filters
		add_filter( 'bbp_get_reply_content', array( $this, 'rc_bbps_add_shortcodes_support' ), 10, 6 );
		add_filter( 'bbp_get_topic_content', array( $this, 'rc_bbps_add_shortcodes_support' ), 10, 6 );
		
		// shortcodes
		add_shortcode( 'private',  array( $this, 'rc_bbps_private_shortcode' ) );
		add_shortcode( 'alert', array( $this, 'rc_bbps_alert_shortcode' ) );
		
	}

	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		
		load_plugin_textdomain( 'rc_bbpps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		
	}
	
	
	/**
	 * Load the plugin's CSS files
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register_plugin_styles() {
	
		$css_path = plugin_dir_path( __FILE__ ) . 'css/style.css';
	    wp_enqueue_style( 'rc_bbpps', plugin_dir_url( __FILE__ ) . 'css/style.css', filemtime( $css_path ) );
	    
	}
	
	
	/**
	 * Adds BBpress shortcode support
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	public function rc_bbps_add_shortcodes_support( $content, $reply_id ) {
		
		return do_shortcode( $content );
		
	}
	
	
	/**
	 * Create [private]str[/private] shortcode
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	public function rc_bbps_private_shortcode( $atts, $content = null, $reply_id ) {
	
		$topic_author = bbp_get_topic_author_id();
		$reply_author = bbp_get_reply_author_id( $reply_id );
		
		if( $topic_author != bbp_get_current_user_id() && $reply_author != bbp_get_current_user_id() && !current_user_can( 'publish_forums' ) ) {
			return '<span class="bbpps-message warning">'.__('This content is private', 'rc_bbpps').'</span>';
		} else {
			return '<span class="bbpps-message notice">'.$content.'</span>';

		}
		
	}

	
}

// instantiate plugin's class
$GLOBALS['bbp_private_shortcodes'] = new BBP_private_shortcodes();
