$(document).ready(function() {
    window.setInterval(function() {
	$.ajax({
	    url : '/checkSongEnd.php',
	    type : 'GET',
	    dataType : 'json',
	    success : function(response) {
		console.log(response);
		$('#nowPlayingInfo').html(response.artist[0]+' - '+response.track[0]+' ('+response.time+'/'+response.timeTotal+')');
		$.ajax({
		    url : '/playNewSong.php',
		    type : 'POST',
		    data : {data:response.xml},
		    success:function(response) {
			//console.log(response);
		    }
		});
	    }
	});
    },1000);


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
		      url : '/vote.php',
		      type : 'GET',
		      data : {itemName:ui.item.itemName,
			      location:ui.item.location,
			      val:'up'},
		      success : function(response) {
			  console.log(response);
			  location.reload();
		      }
		  });
        },
	
    });

});