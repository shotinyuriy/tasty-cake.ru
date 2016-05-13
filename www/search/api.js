function addCategoryClick() {

	$( "div.menu-category" ).hover ( function( event ) {
		$( this ).find( "h6" ).css("color", "#E40108");
		
		var categoryId = $( this );
		categoryId = categoryId ? categoryId[0].id : undefined;
		if( categoryId ) {
			window.categoryId = categoryId;
		}
	}, function( event ) {
		$( this ).find( "h6" ).css("color", "#FFF");
	});

	$( "div.menu-category" ).click ( function ( event ) {
		
		var categoryId = window.categoryId;
		if( categoryId ) {
			
			$.ajax({
				type: "GET",
				url: window.home_href+"core/set-current-category.php",
				data: { categoryId: categoryId }
			})
				.done( function( data ) {
					window.location.href = window.home_href+"menu";
				})
				.fail ( function( data, textStatus ) {
					alert( "Ошибка при выборе категории!" );
				});
		}
	});
};

function doSearch() {
	

	var query = window.location.search;
	
	$.ajax( {
		url: window.home_href+"core/search.php"+query,
		success: function( data ) {
			
			$( "#info" ).html( data );
			
			var substrStart = "?query=".length;
			$( "input[name='query']" ).val( decodeURI ( query ).substr( substrStart ) );
			
			addToCartListener();
			
			addCategoryClick();
		}
	} );
	
};

$( document ).ready( function() {
	doSearch();
});