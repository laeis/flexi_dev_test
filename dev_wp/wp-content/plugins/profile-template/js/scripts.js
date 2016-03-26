( function ($){
	$( document ).ready( function(){
		var avatar_attach = function( thumb_id ) {
			wp.media.post( 'add_avatar_image', {
			 	thumbnail_id: thumb_id,
			} ).done( function ( html ) {
				$( '#avatar_img' ).attr( 'src', html.url );
				$( 'input[name="avatar-img-id"]' ).attr( 'value', html.id );
			} );
		};
		/*  Initialization windows loader for region images */
		var avatar_uploader = wp.media( {
			id:     'background_image_frame'
		} );
		avatar_uploader.on( 'select', function() {
			var attachments = [];
			avatar_uploader.state().get( 'selection' ).forEach(function( i ) {
		  		attachments.push( i.id );
			} );
			avatar_attach( attachments );
		});
		$( '#input_avatar' ).on( 'click', function( e ) {
			e.preventDefault();
			e.stopPropagation();
			avatar_uploader.open();
		});
	});
})( jQuery );

		