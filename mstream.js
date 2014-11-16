$(document).ready(function() {
    window.setInterval(function() {
	$.ajax({
	    url : '/checkSongEnd.php',
	    type : 'GET',
	    dataType : 'json',
	    success : function(response) {
		console.log(response);
	    }
	});
    },10000);

    /*
   // Extend the autocomplete widget with a new "suggest" option.
    $.widget( "app.autocomplete", $.ui.autocomplete, {
        
        options: {
            suggest: false    
        },
        
        // Called when the autocomplete menu is about to be displayed.
        _suggest: function( items ) {
            
            // If there's a "suggest" function, use it to render the
            // items. Otherwise, use the default _suggest() implementation.
            if ( $.isFunction( this.options.suggest ) ) {
                return this.options.suggest( items );
            }
            
            this._super( items );
            
        },
        
        // Called when the autocomplete menu is about to be hidden.
        _close: function( e ) {
        
            // If there's a "suggest" function, call it with an
            // empty array so it can clean up. Otherwise, use the
            // default _close() implementation.
            if ( $.isFunction( this.options.suggest ) ) {
                this.options.suggest( [] );
                return this._trigger( "close", e );
            }
            
            this._super( e )
            
        }
        
    });
    */

    $('#searchBox').autocomplete({
        source:'/searchSongs.php',
	/*
        suggest: function( items ) {
            var $div = $( ".results" ).empty();
	    var list = '';
            $.each( items, function() {
		list += this.itemName+'<form style="display:inline" method="post" action="playSong.php"><input type="hidden" name="itemName" value="'+this.itemName+'"><input type="hidden" name="location" value="'+this.location+'"><input type="submit" value="Play Song"></form><br />';
            });
	    $('.results').html(list);
        },


	open: function(e, ui) {
	    console.log(e);
            var list = '';
            var results = $('ul.ui-autocomplete.ui-widget-content li');
            results.each(function() {
		//console.log(this);
                list += $(this).html() + '<br />';
            });
            $('#results').html(list);
	},*/

	      select:function(event,ui) {
		  $.ajax({
		      url : '/playSong.php',
		      type : 'POST',
		      data : {itemName:ui.item.itemName,
			      location:ui.item.location},
		      success : function(response) {
			  console.log(response);
		      }
		  });
        },
	
    });

});