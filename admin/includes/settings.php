<?php

class trackWPSettings {


	function __construct() {

		add_action( 'admin_head',  					array( $this, 'admin_assets' ) );
		add_action( 'admin_menu',     				array( $this, 'menu' ) );
		add_action( 'network_admin_menu',   		array( $this, 'menu' ) );
		add_action( 'wp_ajax_trackwp-settings', 	array( $this, 'process_settings' ) );

	}

	/**
	 * Load some assets for the appropriate pages in admin
	 *
	 * @since 1.0
	 */
	function admin_assets() {

		$screen = get_current_screen();

		$pages = array(
			'settings_page_trackwp',
			'settings_page_trackwp-network'
		);

		foreach ( $pages as $page ) {
			wp_enqueue_script( 'trackwp-settings-script', TRACKWP_URL.'/admin/assets/js/trackwp.js', array( 'jquery' ), TRACKWP_VERSION, true );
			wp_enqueue_style( 'trackwp-settings-style', TRACKWP_URL.'/admin/assets/css/trackwp.css', TRACKWP_VERSION );
		}
	}

	/**
	 * Add a submenu page to the "Settings" tab if network activated, otherwise add to our menu page
	 *
	 * @since 1.0
	 */
	function menu() {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			add_submenu_page( 'settings.php', __( 'TrackWP', 'trackwp' ), __( 'TrackWP', 'trackwp' ), 'manage_network', 'trackwp', array( $this, 'settings' ) );

		} else {

			add_submenu_page( 'tools.php', __( 'TrackWP', 'trackwp' ), __( 'TrackWP', 'trackwp' ), 'manage_options', 'trackwp', array( $this, 'settings' ) );

		}
	}

	/**
	 * Submenu page callback
	 *
	 * @since 1.0
	 */
	function settings() {

		echo self::trackwp_settings_form();
	}

	/**
	 * Save settings via ajax
	 *
	 * @since 1.0
	 */
	function process_settings() {
		// bail out if current user isn't and administrator and they are not logged in
		if ( !current_user_can( 'manage_options' ) || !is_user_logged_in() )
			return;

		if ( isset( $_POST['action'] ) && 'trackwp-settings' == $_POST['action'] && check_admin_referer( 'nonce', 'trackwp_settings' ) ) {

			$options = isset( $_POST['trackwp'] ) ? $_POST['trackwp'] : false;

			$options = array_map( 'sanitize_text_field', $options );

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

				update_site_option( 'trackwp', $options );

			} else {

				update_option( 'trackwp', $options );
			}

			wp_send_json_success();

		} else {

			wp_send_json_error();

		}

		die();

	}

	/**
	 * Draw the settings form
	 *
	 * @since 1.0
	 */
	function trackwp_settings_form() {

		if ( !is_user_logged_in() )
			return;


		$segment_key   = trackwp_get_option( 'segment_write_key', 'trackwp' );
		$logging   = 'on' == trackwp_get_option( 'file_logging', 'trackwp' );

?>
		<div class="wrap">

	    	<h2><?php _e( 'TrackWP Settings', 'trackwp' );?></h2>

			<form id="trackwp-settings-form" class="trackwp--form-settings" method="post" enctype="multipart/form-data">

				<div class="trackwp-settings--option-wrap">
					<div class="trackwp-settings--option-inner">
						<label><?php _e( 'Segment Write Key', 'trackwp' );?></label>
						<span class="trackwp--setting-description"><?php _e( 'Segment Write Key.', 'trackwp' );?></span>
						<input required type="password" name="trackwp[segment_write_key]" id="trackwp[segment_write_key]" value="<?php echo esc_attr( $segment_key );?>" >
					</div>
				</div>

				<div class="trackwp-settings--option-wrap">
					<div class="trackwp-settings--option-inner">
						<label><?php _e( 'Use File Logging', 'trackwp' );?></label>
						<span class="trackwp--setting-description"><?php _e( 'Enable File Logging.', 'trackwp' );?></span>
						<input type="checkbox" name="trackwp[file_logging]" id="trackwp[file_logging]" <?php echo checked( $logging, 1 );?> >
					</div>
				</div>

				<div class="trackwp-settings--submit">
				    <input type="hidden" name="action" value="trackwp-settings" />
				    <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'trackwp' );?>" />
					<?php wp_nonce_field( 'nonce', 'trackwp_settings' ); ?>
				</div>
			</form>

		</div><?php

	}
}
new trackWPSettings;