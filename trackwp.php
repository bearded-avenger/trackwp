<?php
/**
 *
 * @package   TrackWP
 * @author    Nick Haskins <email@nickhaskins.com>
 * @license   GPL-2.0+
 * @link      http://nickhaskins.com
 * @copyright 2015 Aesopinteractive LLC2015 Aesopinteractive LLC
 *
 * @wordpress-plugin
 * Plugin Name:      TrackWP
 * Plugin URI:        http://nickhaskins.com
 * Description:       A tracking framework for segmentio
 * Version:           0.5 - beta
 * Author:            Nick Haskins
 * Author URI:        http://nickhaskins.com
 * Text Domain:       trackwp-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set some constants
define('TRACKWP_VERSION', '0.5');
define('TRACKWP_DIR', plugin_dir_path( __FILE__ ));
define('TRACKWP_URL', plugins_url( '', __FILE__ ));
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-trackwp.php' );

register_activation_hook( __FILE__, array( 'TrackWPInit', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'TrackWPInit', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'TrackWPInit', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-trackwp-admin.php' );
	add_action( 'plugins_loaded', array( 'TrackWP_Admin', 'get_instance' ) );

}
