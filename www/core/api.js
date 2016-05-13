function counter() {
	$.ajax( {
		url: "../core/c-counter.php",
		success: function( data ) {
			console.log( data );
		}
	} );
};


function addPortionEditListener() {
	$( ".portion-edit" ).click( function( e ) {
		e.preventDefault();
		tds = $( this ).parent().parent().find( "td" );
		
		console.log( tds );
		
		try {
			var portionId = $( this )[ 0 ].id;
			var amount = tds[ 1 ].innerHTML;
			var gramms = tds[ 2 ].innerHTML;
			var milliliters = tds[ 3 ].innerHTML;
			var price = tds[ 4 ].innerHTML;
			
			$( "input[name='portion_id']" ).val( portionId );
			$( "input[name='amount']" ).val( amount );
			$( "input[name='gramms']" ).val( gramms );
			$( "input[name='milliliters']" ).val( milliliters );
			$( "input[name='price']" ).val( price );
			$( "td.divider" )[ 0 ].innerHTML = "Изменение порции";
		} catch ( e ) {
			console.log( e.message );
		}
	} );
};


function addPageListener() {
	$( ".pagination a" ).click( function( e ) {
		e.preventDefault();
		
		var url = $( this )[0].href;
		loadGoodByCategory( url );
		
	} );
};

function addCloseFormListener() {
	$( ".close_form" ).click( function( e ) {
		e.preventDefault();
		$( ".blocking" ).removeClass("visible");
		$( ".blocking" ).addClass("hidden");
	} );
};

function addFileChangeListener() {
	$( "input[name='image_url']" ).change( function( e ) {
		var image_url = $( this ).val();
		console.log( $( ".image_url" ) );
		$( ".image_url" ).attr("src", "" );
		$( ".image_url" ).attr("height", "100px" );
		$( ".image_url" ).attr("alt", "Новое изображение "+image_url );
	} );
};

function addSubmitValidationListener() {
	$( "#add_good_form" ).submit( function( e ) {
		e.preventDefault();
		
		var $form = $( this );
		var error = "";
		
		if( $form.length == 1 ) {
			var name = $form.find( "input[name='name']" ).val();
			if( !name || name.trim() == '' ) {
				error += "Введите название товара!<br>";
			}
			
			if( error.length > 0 ) {
				var newDiv = $( "#validation_errors" )[ 0 ];
				if( !newDiv ) {
					newDiv = document.createElement( 'div' );
					newDiv.id = 'validation_errors';
					newDiv.className = 'error';
				}
				
				newDiv.innerHTML = '<p>'+error+'</p>';
				$form[ 0 ].appendChild( newDiv );
			} else {
				$form.unbind();
				$form.submit();
			}
		}
	} );
	
	$( "#add_category_form" ).submit( function( e ) {
		e.preventDefault();
		
		var $form = $( this );
		var error = "";
		
		if( $form.length == 1 ) {
			var name = $form.find( "input[name='name']" ).val();
			if( !name || name.trim() == '' ) {
				error += "Введите название категории!<br>";
			}
			
			if( error.length > 0 ) {
				var newDiv = $( "#validation_errors" )[ 0 ];
				if( !newDiv ) {
					newDiv = document.createElement( 'div' );
					newDiv.id = 'validation_errors';
					newDiv.className = 'error';
				}
				
				newDiv.innerHTML = '<p>'+error+'</p>';
				$form[ 0 ].appendChild( newDiv );
			} else {
				$form.unbind();
				$form.submit();
			}
		}
	} );
};

function addEditListeners() {
					
		$( ".edit" ).click( function( e ) {
			e.preventDefault();
			$( "#editorForm").modal('show');
			
			var url = $( this )[ 0 ].href;
			
			$.ajax( {
				url: url,
				success: function( data ) {
						
					$( "#editor" ).html( data );
					
					addFileChangeListener();
					addPortionEditListener();
					addSubmitValidationListener();
				}
			} );
			
		} );
};

function addToCartListener() {
	$( ".tocart > input[type='submit']" ).click( function( e ) {
		e.preventDefault();
		var portionId = $( this ).parent().find( "input[name='portionId']" )[ 0 ];
		var portionId = portionId.value;
		
		$.ajax( {
			url: '../core/c-cart.php',
			data: {
				portionId: portionId,
				method: "add"
			},
			success: function( data ) {
				showCartTotalSum();
			}
		} );
	} );
};

function showCartTotalSum() {

	$.ajax({
		url: "../core/c-cart.php",
		data: {},
		success: function( data ) {
			if( data ) data = data.trim();
			$( "#top_cart > span" ).html( data );
		}
	})
		.fail( function( data ) {
			alert( "Ошибка при выборе категории!" );
		});
};

function loadGoodByCategory( url ) {
	var data = {};
	
	data.cms = true;
	
	if( !url ) {
		url = '../core/c-good.php';
	} 
	
	$.ajax( {
		url: url,
		data: data,
		success: function( data ) {
			if( data ) data = data.trim().replace('&#65279;','');
			$( "#menu" ).html( data );
			
			addToCartListener();
			addEditListeners();
			addSearchOrdersListener();
			addPageListener();
		}
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
		
		loadGoodByCategory( url );
		
		$( "a.menu-category-selected" )
			.removeClass("menu-category-selected")
			.addClass("menu-category")
			.click( categorySelection )
			.unbind( "click", categorySelectedSlideToggle );
		
		$( "li.menu-category-selected" )
			.removeClass("menu-category-selected")
			.addClass("menu-category");
		
		$( this )
			.removeClass( "menu-category" )
			.addClass( "menu-category-selected" )
			.unbind( "click", categorySelection )
			.click( categorySelectedSlideToggle );
		
		$( this ).parent()
			.removeClass( "menu-category" )
			.addClass( "menu-category-selected" );
			
		$( this ).parent().find( ".sub-category" ).slideDown();
		
	}
};

function stateChange( e ) {
	e.preventDefault();
	var url = $( this )[ 0 ].href;
	
	$.ajax( {
		url: url,
		success: function( data ) {
			$( "#orders_div" ).html( "<h6>Статус изменен! Обновите список!</h6>" );
		}
	} );
	
};

function addSearchOrdersListener() {
	$( "#search_orders_form" ).submit( function( e ) {
		e.preventDefault();
		var params = $( this ).serialize();
		
		var url = $( this )[ 0 ].action;
		url = url+'?'+params;
		
		$.ajax( {
			url: url,
			success: function( data ) {
				if( data ) data = data.trim();
				$( "#orders_div" ).html( data );
				
				addStateChangeListener();
				 addEditListeners();
			}
		} );
	} );
};

function addOnclickListener() {
	$( "a.menu-category, a.menu-category-selected" ).click ( categorySelection );
};

function addStateChangeListener() {
	$( ".st-change" ).click( stateChange );
};

$( document ).ready( function() {
	
	counter();
	
	$.ajax( {
		url: "../core/c-category.php",
		data: { view: "list", cms: "true" },
		success: function( data ) {
			
			$( "#categories" ).html( data ); 
			
			$( ".sub-category" ).slideUp();
			
			addOnclickListener();
			addEditListeners();

		}
	} );
	
	loadGoodByCategory();
	
	$( ".blocking" ).removeClass("visible");
	$( ".blocking" ).addClass("hidden");
	
	addCloseFormListener();

	$("#userinfo").click(function(event) {
		event.preventDefault();

	});
});