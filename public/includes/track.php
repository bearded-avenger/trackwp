<?php

/**
*	Class responsible for sending track and identify events
*
*	@since 0.5
*/
class trackWP {

	public function __construct(){

		$key   = trackwp_get_option( 'segment_write_key', 'trackwp' );
		$logging   = trackwp_get_option( 'file_logging', 'trackwp' );

		if ( !empty( $key ) ) {

			class_alias( 'Segment', 'TWPAnalytics' );

			$args = array(
				'consumer' => 'file',
		    	'filename' => TRACKWP_DIR.'analytics.log'
			);

			$options = 'on' == $logging ? $args : false;

			TWPAnalytics::init(	trim( $key ), $options );

		}

	}

	/**
	*	Identify a user
	*
	*	@param $user_id int id of the user to identify - if empty gets the current use rid
	*	@param $traits array data to be passed - if the user is logged in then it grabs their name and email
	*
	*	@since 0.5
	*/
	public static function identify_user( $user_id = '', $traits = array() ) {

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
			$user 	= get_userdata( $user_id );
		}

		# Check for traits
		if ( empty( $traits ) && is_user_logged_in() ) {

			$traits = array(
				'firstName' 	=> $user->first_name,
				'lastName' 		=> $user->last_name,
				'email' 		=> $user->user_email,
				'createdAt'		=> strtotime( $user->user_registered ),
				'username' 		=> $user->user_login
			);
		}

		# User data to be passed
		$args = array(
			'userId' => $user_id,
			'traits' => $traits
		);

		TWPAnalytics::identify( $args );

		return $args;
	}

	/**
	*	Track an event
	*
	*	@param $event string an event name
	*	@param $props array data to be passed - if the user is logged in then it grabs their name and email
	*	@param $traits array data to be passed - if the user is logged in then it grabs their name and email
	*	@param $user_id int id of the user to track the event for - if no user id (not part of site) then use a session id
	*
	*	@since 0.5
	*/
	public static function track_event( $event = '', $props = array(), $traits = array(), $user_id = '' ) {

		# If no event name is passed, return
		if ( empty( $event ) ) {
			return;
		}

		if ( empty( $user_id ) ) {
			$user_id = rand();
		}

		if ( empty( $traits ) ) {

			$traits = array(
				'userId' => is_user_logged_in() ? get_current_user_id() : rand()
			);

		}

		TWPAnalytics::track(
			array(
				'userId' 		=> $user_id,
				'event' 		=> $event,
				'traits' 		=> $traits,
				'properties' 	=> $props
			)
		);
	}

	/**
	*	Track a page
	*
	*	@param $pagename string name of the page to be tracked
	*	@param $props array data to be passed - if the user is logged in then it grabs their name and email
	*	@param $traits array data to be passed - if the user is logged in then it grabs their name and email
	*
	*	@since 0.5
	*/
	public static function track_page( $pagename = '', $props = array(), $traits = array() ) {

		TWPAnalytics::page(
			array(
				'userId' 		=> is_user_logged_in() ? get_current_user_id() : rand(),
				'name' 			=> $pagename,
				'properties' 	=> $props
			)
		);
	}

}
new trackWP();
