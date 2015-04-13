This plugin builds an API interface to allow you to hook and and send events to segmentio. Here are a few examples:

```
add_action('wp_login', 'my_track_login', 10, 2);
function my_track_login( $user_login, $user ) {

	$user_id = $user->ID;
	$user_data = get_userdata( $user_id );

	$traits = array(
		'userId' 	=> $user_id,
		'firstName' => $user_data->first_name,
		'lastName' 	=> $user_data->last_name,
		'username' 	=> $user_login,
		'email' 	=> $user_data->user_email
	);

	$props = array();

	trackWPInit::identify( $user_id, $traits );
	trackWPInit::track( 'User Login', $props, $traits, $user_id );

}

```