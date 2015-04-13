This plugin builds an API interface to allow you to hook and and send events to segmentio. It currently supports three interface calls:  

`trackWP::identify_user( $user_id = '', $traits = array() )`  
`trackWP::track_event( $event = '', $props = array(), $traits = array(), $user_id = '' )`  
`trackWP::track_page( $pagename = '', $props = array(), $traits = array() )`  

Here are a few examples of how to track things and such.  

```
/**
*	Track a Login
*/
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

	trackWP::identify_user( $user_id, $traits );
	trackWP::track_event( 'user_login', $props, $traits, $user_id );

}

/**
*	Track a purchase with Easy Digital Downloads
*/
add_action( 'edd_complete_purchase', 'my_track_purchase', 10, 1 );
function my_track_purchase( $payment_id ) {

	$userInfo = edd_get_payment_meta_user_info( $payment_id);
	$user_id = get_current_user_id();

	$traits = array(
		'userId' 	=> is_user_logged_in() ? $user_id : session_id(),
		'firstName' => $userInfo[ 'first_name' ],
		'lastName' 	=> $userInfo[ 'last_name' ],
		'email' 	=> $userInfo[ 'email' ],
	);

	$downloads = edd_get_payment_meta_cart_details( $payment_id );
	$products = array();

	foreach( $downloads as $download ) {
		$products[] = get_the_title( $download['id'] );
	}

	$props = array(
		'trans_id' 	=> edd_get_payment_transaction_id( $payment_id ),
		'total' 	=> edd_get_payment_amount( $payment_id ),
		'time' 		=> strtotime( edd_get_payment_completed_date( $payment_id ) ),
		'products' => $products
	);

	trackWP::track_event( 'purchased', $props, $traits, $user_id );
}

```