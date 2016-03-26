( function ( $ ){
    $( document ).ready( function() {
        var files;
        // Add events
        $( 'input[type=file]' ).on( 'change', prepareUpload );

        // Grab the files and set them to our variable
        function prepareUpload(event) {
            files = event.target.files;
            $('#avatar_danger').text('');
            $('#avatar_success').text('');
        }

        $('form#profile_avatar_form').on( 'submit', uploadFiles );

        // Catch the form submit and upload the files
        function uploadFiles(event) {
            event.stopPropagation(); // Stop stuff happening
            event.preventDefault(); // Totally stop stuff happening

            // START A LOADING SPINNER HERE

            // Create a formdata object and add the files
            if( typeof files !== 'undefined' ){
                var data = new FormData();
                data.append( 0, files[0] );
                data.append( 'avatar-upload-action', true );
                $.ajax({
                    url: 'profileAvatar',
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function( data, textStatus, jqXHR ) {
                        if(typeof data.error === 'undefined') {
                            $( '#avatar_success' ).text( 'SUCCESS: ' + data.success );
                            $( '#avatar_img' ).attr( 'src', data.formData.avatar_link );
                            $( 'input[name="avatar-img-id"]' ).attr( 'value',  data.formData.avatar_id );
                        } else {
                            // Handle errors here
                            $( '#avatar_danger' ).text( 'ERRORS: ' + data.error );
                        }
                       
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        // STOP LOADING SPINNER
                    }
                });
            }
        }
    } )
} )( jQuery );