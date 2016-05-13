function counter() {
	$.ajax( {
		url: window.home_href+"core/c-counter.php",
		success: function( data ) {
			console.log( data );
		}
	} );
};

function addToCartListener() {
	$( ".tocart > *[type='submit']" ).click( function( e ) {
		e.preventDefault();
		var portionId = $( this ).parent().find( "input[name='portionId']" )[ 0 ];
		portionId = portionId.value;
		
		var select = document.createElement( 'div' );
		for( var i = 1 ; i <= 14 ; i++ ) {
			var tmpDiv = document.createElement( 'div' );
			tmpDiv.innerHTML = ''+i;
			select.appendChild( tmpDiv );
			tmpDiv.className = 'amt slideDown';
		}
		select.className = 'amtsel';
		
		var form = $( this ).parent()[ 0 ];
		form.appendChild( select );
		
		$( ".amtsel" ).mouseleave( function( e ) {
			var amtsel = $( ".amtsel" );
			var select = amtsel[ 0 ];
			var form = amtsel.parent()[ 0 ];
			$( ".slideDown").removeClass("slideDown").addClass("slideUp");
			setTimeout(function(){form.removeChild( select );}, 900);
		} );
		
		$( ".amt" ).click( function( e ) {
			var amount = $( this )[ 0 ].innerHTML;
			$( ".slideDown").removeClass("slideDown").addClass("slideUp");
			setTimeout(function(){form.removeChild( select );}, 900);
			
				$.ajax( {
					url: '../core/c-cart.php',
					data: {
						portionId: portionId,
						amount: amount,
						method: "add"
					},
					success: function( data ) {
						showCartTotalSum();
					}
			} );
		} );
	} );
};

function showCartTotalSum() {

	$.ajax({
		url: window.home_href+"core/c-cart.php",
		data: {},
		success: function( data ) {
			$( "#cart_total" ).html( data );
		}
	})
		.fail( function( data ) {
			$( "#cart_totaln" ).html( "0 руб." );
		});
};

function loadNews() {
	
	$.ajax( {
		url: window.home_href+"core/c-news.php",
		success: function( data ) {
		
			if( data ) data = data.trim();
			$( "#news" ).html( data );
			
	 		//addNewsScrollListener();
		}
	} );
};

function addSearchListener() {
	
	$( "#search-button" ).click( function( e ) {
		e.preventDefault();
		
		var url = $( "#search-form" ).serialize();
		url = window.home_href+"search?"+url;
		
		window.location.href = url;
		
	} );
};

$( document ).ready( function() {
	
	window.home_href = "http://tasty-cake.ru/";
	
	counter();
	
	$( "a.main-nav" ).each( function( idx ) {
		var href = $( this ).attr( "href" );
		
		href = href.replace( "http://tasty-cake.ru/", window.home_href );
		
		$( this ).attr( "href", href );
		
	} );
	
	$( "#top_cart" ).click( function( e ) {
		window.location.href = window.home_href+"cart";
	} );
	
	showCartTotalSum();
	
	loadNews();
	
	addSearchListener();
} );