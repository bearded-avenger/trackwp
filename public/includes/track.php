<?php

/**
*	Class responsible for sending track and identify events
*
*	@since 1.0
*/
class trackWP {

	public function __construct(){

		$options = get_option( 'trackwp' );

		$key = !empty( $options['segment_write_key'] ) ? trim( $options['segment_write_key'] ) : false;

		class_alias( 'Segment', 'TrackWP' );
		TrackWP::init( $key );

	}

	/**
	*	Identify a user
	*
	*	@param $user_id int id of the user to identify - if empty gets the current use rid
	*	@param $traits array data to be passed - if the user is logged in then it grabs their name and email
	*
	*	@since 1.0
	*/
	public static function identify( $user_id = '', $traits = array() ) {

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
				'username' 		=> $user->user_login
			);
		}

		# User data to be passed
		$args = array(
			'userId' => $user_id,
			'traits' => $traits
		);

		TrackWP::identify( $args );

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
	*	@since 1.0
	*/
	public static function track( $event = '', $props = array(), $traits = array(), $user_id = '' ) {

		# If no event name is passed, return
		if ( empty( $event ) ) {
			return false;
		}

		if ( empty( $user_id ) ) {
			$user_id = session_id();
		}

		TrackWP::track(
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
	*	@since 1.0
	*/
	public static function page( $pagename = '', $props = array(), $traits = array() ) {

		TrackWP::page(
			array(
				'userId' 		=> is_user_logged_in() ? get_current_user_id() : session_id(),
				'name' 			=> $pagename,
				'properties' 	=> $props
			)
		);
	}

}