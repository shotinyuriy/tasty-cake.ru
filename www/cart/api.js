function addIsBirthdayListener() {
	$( "input[name='isBirthday']" ).change( function( e ) {
		var isBirthday = $( this ).val();
		
		$.ajax( {
			url: "../core/c-cart.php",
			data: {
				method: "birthday",
				isBirthday: isBirthday
			},
			success: function( data ) {
				if( data ) data = data.trim();
				
				$( "#cart_content" ).html( data );
				addAmountButtonHandlers();
				addCartNavListeners();
				addIsBirthdayListener();
			}
		} );
	} );
}

function addAmountVal( event, value ) {
	
	try {
		var target = event.target;
		var targetId = target.id;
		
		var portionId = targetId.split("_")[ 1 ];
		var amountId = '#amt_'+portionId;
		var amountTextBox = $( amountId )[ 0 ];
		var amount = amountTextBox.value;
		
		if( amount > 1 && value < 0 || value > 0 ) {
			
			amountTextBox.value = amount*1 + value;
			
			$.ajax( {
				url: '../core/c-cart.php',
				data: {
					portionId: portionId,
					method: "add",
					amount: value
				},
				success: function( data ) {
					console.log( 'data: ' + data.trim() );
					
					if( data.trim() == 'renew' ) {
						showCartDetails();
					} else {
						var strings = data.split("|");
						var rowCost = strings[ 0 ];
						var totalCost = strings[ 1 ];
						$( "#cost_"+portionId ).html( rowCost );
						$( "#total_cost" ).html( totalCost );
					}
				}
			} );
		} else if( value == 0 ) {
		
			$.ajax( {
				url: '../core/c-cart.php',
				data: {
					portionId: portionId,
					method: "delete"
				},
				success: function( data ) {
					showCartDetails();
				}
			} );
		}
		
	} catch( ex ) {
		console.log( ex );
	}
};

function addAmountButtonHandlers() {
	$( ".increase" ).click( function ( event ) {
		addAmountVal( event, 1 );
	} );
	$( ".decrease" ).click( function ( event ) {
		addAmountVal( event, -1 );
	} );
	
	$( ".delete" ).click( function ( event ) {
		addAmountVal( event, 0 );
	} );
}

function cartSave() {
	
	var $form = $( "#cart_order_form" );
	
	var phoneNumber = $form.find( "#phoneNumber" ).val();
	var customerName = $form.find( "#customerName" ).val();
	var selfTake = $form.find( "input[name='selfTake']:checked" ).val();
	var address = $form.find( "#address" ).val();
	
	var error = '';
	
	if( !phoneNumber || phoneNumber.trim() == '' ) {
		error += 'Укажите номер телефона!<br>';
	} else if ( !/\d{10}/.test( phoneNumber) ) {
		error += 'Номер телефона может содержать только 10 цифр!<br>';
	}
	
	if( selfTake != 1 && ( !address || address.trim().length < 8 ) ) {
		error += 'Укажите адрес для доставки!<br>';
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
		
		$.ajax( {
			type: 'POST',
			url: '../core/c-cart.php',
			data: {
				method: 'save',
				phoneNumber: phoneNumber,
				customerName: customerName,
				selfTake: selfTake,
				address: address
			},
			success: function( data ) {
			
				if( data ) data = data.trim();
				$( "#cart_content" ).html( data );
				
				if( $( "#cart_content > .error" ).length == 0 ) {
					window.location.href = window.home_href+'cart';
				}
				
				
			}
		} );
	}
};

function clearCartDetails() {

	$.ajax( {
		url: '../core/c-cart.php',
		data: {
			method: 'clear'
		},
		success: function( data ) {
			$( "#cart_content" ).html( data );
		}
	} );
};


function cartToOrder() {

	$.ajax( {
		url: '../core/c-cart.php',
		data: {
			method: 'cart_order'
		},
		success: function( data ) {
			$( "#cart_content" ).html( data );
			
			addCartNavListeners();
			
			$( "input[name='selfTake']" ).change( function( e ) {
				 if( $( this ).val() == 0) {
				 	$( "#address" ).css("visibility","visible");
				 	$( "#addressLabel" ).css("visibility","visible");
				 } else {
				 	$( "#address" ).css("visibility","hidden");
				 	$( "#addressLabel" ).css("visibility","hidden");
				 }
			} );
		}
	} );
};

function addCartNavListeners() {
	$( "#cart_cancel, #cart_new" ).click( function( e ) {
		e.preventDefault();
		clearCartDetails();
	} );
	
	$( "#cart_next" ).click( function( e ) {
		e.preventDefault();
		window.location.href = '../cart?step=2';
	} );
	
	$( "#cart_back" ).click( function( e ) {
		e.preventDefault();
		window.location.href = '../cart';
		
		console.log( window.location.href );
	} );
	
	$( "#cart_save" ).click( function( e ) {
			e.preventDefault();
			cartSave();
	} );
};

function showCartDetails() {

	$.ajax( {
		url: '../core/c-cart.php',
		data: {
			method: 'showDetails'
		},
		success: function( data ) {
			$( "#cart_content" ).html( data );
			
			addAmountButtonHandlers();
			addCartNavListeners();
			addIsBirthdayListener();
		}
	} );
};


$( document ).ready( function() {
	
	if( /step=2/.test( window.location.search ) ) {
		cartToOrder();
	} else {
		showCartDetails();
	} 
	
});