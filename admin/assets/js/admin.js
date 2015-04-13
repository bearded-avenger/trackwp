jQuery(document).ready(function($){

	// entry handler
  	$('#trackwp-settings-form').submit(function(e) {

  		var $this = $(this);

  		e.preventDefault();

		$this.find(':submit').attr( 'disabled','disabled' );

  		var data = $this.serialize();

	  	$.post( ajaxurl, data, function(response) {

	  		if ( response.success ) {

	  			$this.find(':submit').addClass('saved');
	  			$this.find('.trackwp-settings--submit').append('<div class="trackwp-settings--confirm success">Settings Saved!</div>');

	  			setTimeout( function(){
	  				$this.find(':submit').removeClass('saved');
	  				$this.find('.trackwp-settings--confirm').remove();
	  				$this.find(':submit').attr( 'disabled',false );
	  			}, 2000 );

	  		} else {

	  			$this.find('.trackwp-settings--submit').append('<div class="trackwp-settings--confirm error">Something went wrong! :(</div>');

	  		}
	    });

    });

});