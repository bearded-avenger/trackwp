<?php
/**
 * Grab an optoin from our settings
 *
 * If we're on multsite we'll grab the site option which is stored in the main blogs site option tables, otherwise
 * we'll grab the option which is stored on the single blogs option tables
 *
 * @param unknown $option  string name of the option
 * @param unknown $section string name of the section
 * @param unknown $default string/int default option value
 * @return the option value
 * @since 1.0
 */
function trackwp_get_option( $option, $section, $default = '' ) {

	if ( empty( $option ) )
		return;

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {

		$options = get_site_option( $section );

	} else {

		$options = get_option( $section );
	}

	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}

	return $default;
}