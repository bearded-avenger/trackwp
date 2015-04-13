<?php
/**
 * TrackWP
 *
 * @package   TrackWP_Admin
 * @author    Nick Haskins <email@nickhaskins.com>
 * @license   GPL-2.0+
 * @link      http://nickhaskins.com
 * @copyright 2015 Aesopinteractive LLC
 */

/**
 * @package TrackWP_Admin
 * @author  Nick Haskins <email@nickhaskins.com>
 */
class TrackWP_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = TrackWP::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}
