<?php
/**
 * Plugin Name: Legalmonster Cookie Plugin
 * Plugin URI: http://www.legalmonster.com/cookie
 * Description: Collect consent for your cookies, email marketing, privacy policy and T&Cs. Everywhere. Every time.
 * Version: 1.0
 * Author: Legal Monster Aps
 * Author URI: http://www.legalmonster.com
 */

class Legalmonster_Plugin {
	/**
	* Constructor
	*/
	public function __construct() {

		// Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'legalmonster'; // Plugin Folder
        $this->plugin->displayName  = 'Legal Monster Cookie Plugin'; // Plugin Name
        $this->plugin->version      = '1.4.6';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );
        $this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';

		// Hooks
		add_action( 'admin_init', array( &$this, 'registerSettings' ) );
        add_action( 'admin_menu', array( &$this, 'adminPanelsAndMetaBoxes' ) );

        // Frontend Hooks
		add_action( 'wp_footer', array( &$this, 'frontendFooter' ) );

		add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );
	}

	/**
    * Output the Administration Panel
    * Save POSTed data from the Administration Panel into a WordPress option
    */
    function adminPanel() {
		// only admin user can access this page
		if ( !current_user_can( 'administrator' ) ) {
			echo '<p>' . __( 'Sorry, you are not allowed to access this page.', 'legalmonster' ) . '</p>';
			return;
		}

    	// Save Settings
        if ( isset( $_REQUEST['submit'] ) ) {
        	// Check nonce
			if ( !isset( $_REQUEST[$this->plugin->name.'_nonce'] ) ) {
	        	// Missing nonce
	        	$this->errorMessage = __( 'nonce field is missing. Settings NOT saved.', 'legalmonster' );
        	} elseif ( !wp_verify_nonce( $_REQUEST[$this->plugin->name.'_nonce'], $this->plugin->name ) ) {
	        	// Invalid nonce
	        	$this->errorMessage = __( 'Invalid nonce specified. Settings NOT saved.', 'legalmonster' );
        	} else {
	        	// Save
				// $_REQUEST has already been slashed by wp_magic_quotes in wp-settings
				// so do nothing before saving
	    		update_option( 'lm_insert_footer', $_REQUEST['lm_insert_footer'] );
				update_option( $this->plugin->db_welcome_dismissed_key, 1 );
				$this->message = __( 'Settings Saved.', 'legalmonster' );
			}
        }

        // Get latest settings
        $this->settings = array(
			'lm_insert_footer' => esc_html( wp_unslash( get_option( 'lm_insert_footer' ) ) ),
        );

    	// Load Settings Form
        include_once( $this->plugin->folder . '/views/settings.php' );
    }

	function wpdocs_enqueue_custom_admin_style($hook_suffix) {
    // Check if it's the ?page=yourpagename. If not, just empty return before executing the folowing scripts. 
	    if($hook_suffix != 'settings_page_legalmonster') {
	        return;
	    }

	    // Load your css.
	    wp_register_style( 'custom_wp_admin_css', plugins_url('css/style.css',__FILE__ ));
	    wp_enqueue_style( 'custom_wp_admin_css' );
	}

    /**
	* Loads plugin textdomain
	*/
	function loadLanguageFiles() {
		load_plugin_textdomain( 'legalmonster', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	* Outputs script / CSS to the frontend footer
	*/
	function frontendFooter() {
		$this->output( 'lm_insert_footer' );
	}

	/**
    * Register the plugin settings panel
    */
	function adminPanelsAndMetaBoxes() {
    	add_submenu_page( 'options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( &$this, 'adminPanel' ) );
	}

	/**
	* Register Settings
	*/
	function registerSettings() {
		register_setting( $this->plugin->name, 'lm_insert_footer', 'trim' );
	}

	/**
	* Outputs the given setting, if conditions are met
	*
	* @param string $setting Setting Name
	* @return output
	*/
	function output( $setting ) {
		// Ignore admin, feed, robots or trackbacks
		if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}

		// provide the opportunity to Ignore IHAF - both headers and footers via filters
		if ( apply_filters( 'disable_lm', false ) ) {
			return;
		}

		// provide the opportunity to Ignore IHAF - footer only via filters
		if ( 'lm_insert_footer' == $setting && apply_filters( 'disable_lm_footer', false ) ) {
			return;
		}

		// Get meta
		$meta = get_option( $setting );
		if ( empty( $meta ) ) {
			return;
		}
		if ( trim( $meta ) == '' ) {
			return;
		}

		// Output
		echo wp_unslash( $meta );
	}
}

$lm = new Legalmonster_Plugin();