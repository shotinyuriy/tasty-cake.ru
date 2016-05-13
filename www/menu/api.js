function loadGoodByCategory( url ) {
	var data = {};
	
	if( !url ) {
		url = '../core/c-good.php';
	}
	
	$.ajax({
		url: url,
		data: data,
		success: function( data ) {
		
			if( data ) data = data.trim();
			
			$( "#menu" ).html( data );
			
			addToCartListener();
			addPageListener(); 
		}
	});
};

function addPageListener() {
	$( ".pagination a" ).click( function( e ) {
		e.preventDefault();
		
		var url = $( this )[0].href;
		loadGoodByCategory( url );
		
	} );
};

function categorySelectedSlideToggle( e ) {
	e.preventDefault();
	$( this ).parent().find( ".sub-category" ).slideToggle();
}

function categorySelection( e ) {
	e.preventDefault();
	
	var url = $( this )[0].href;
	
	if( url ) {
		
		url += "&pageId=1";
		loadGoodByCategory( url );
		
		$( "a.menu-category-selected" )
			.removeClass("menu-category-selected")
			.addClass("menu-category")
			.click( categorySelection )
			.unbind( "click", categorySelectedSlideToggle );
		
		$( this )
			.removeClass( "menu-category" )
			.addClass( "menu-category-selected" )
			.unbind( "click", categorySelection )
			.click( categorySelectedSlideToggle );
		
		$( this ).parent().find( ".sub-category" ).slideDown();
		
	}
};

function addOnclickListener() {
	$( "a.menu-category, a.menu-category-selected" ).click ( categorySelection );
};

$( document ).ready( function() {
	
	$( "#top_menu" ).slideUp( "slow" );
	
	loadGoodByCategory();
	
	$.ajax( {
		url: "../core/c-category.php",
		data: { view: "list" },
		success: function( data ) {
			
			if( data ) data = data.trim();
			
			$( "#categories" ).html( data ); 
			
			$( ".sub-category" ).slideUp();
			
			addOnclickListener();
			
			$( "#add_category" ).click( function( e ) {
				e.preventDefault();
				$( ".blocking" ).removeClass("hidden");
				$( ".blocking" ).addClass("visible");
			} );
		}
	} );
	
	$( "#close_form" ).click( function( e ) {
		e.preventDefault();
		$( ".blocking" ).removeClass("visible");
		$( ".blocking" ).addClass("hidden");
	} );
});