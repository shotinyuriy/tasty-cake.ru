<?php
session_start();
require_once("m-cart.php");
require_once("m-order-detail.php");
require_once("m-order.php");
require_once("m-portion.php");
require_once("v-cart.php");
require_once("util.php");
	
	$cart = isset( $_SESSION[ "cart" ] ) ? Cart::create_from_assoc( $_SESSION[ "cart" ] ) : $cart = new Cart();
	
	if( isset( $_REQUEST[ "method" ] ) ) {
		$method = StringUtils::convert( $_REQUEST[ "method" ], 'string' );
		
		if( $method == "add" ) {
			
			$portion_id = isset( $_REQUEST[ "portionId" ] ) ? StringUtils::convert( $_REQUEST[ "portionId" ], 'int' ) : null;
			$amount = isset( $_REQUEST[ "amount" ] ) ? StringUtils::convert( $_REQUEST[ "amount" ],  'int') : 1;
			$view = isset( $_REQUEST[ "view" ] ) ? StringUtils::convert( $_REQUEST[ "view" ],  'string') : null;
			
			if( isset( $portion_id ) ) {
				
				$cost_before = $cart->order->total_cost();
				
				if( $cart->order->id && $cart->order->id > 0 ) {
					$cart = new Cart();  
				}
				
				$order_detail = $cart->update_amount_by_portion_id($portion_id, $amount );
				if( $order_detail == null ) {
					
					$portion = Portions::get_portion_by_id( $portion_id );
					
					$order_detail = new OrderDetail( null, $portion->id, $amount, null );
					$order_detail->portion = $portion;
					
					$cart->add_order_details( $order_detail );
				} else {
					CartView::detail_and_total_cost_to_text( $order_detail, $cart, $cost_before );
				}
				
				
				$_SESSION[ "cart" ] = $cart->to_assoc();
			}
			
		} elseif( $method == "delete" ) {
			$portion_id = isset( $_REQUEST[ "portionId" ] ) ? StringUtils::convert( $_REQUEST[ "portionId" ], 'int' ) : null;
			
			if( isset( $portion_id ) ) {
				$cart->delete_by_portion_id( $portion_id );
			}
			
			$_SESSION[ "cart" ] = $cart->to_assoc();
		} elseif( $method == "birthday" ) {
			
			$is_birthday = isset( $_REQUEST[ "isBirthday" ] ) ? StringUtils::convert( $_REQUEST[ "isBirthday" ], 'int' ) : 0;
			
			if( $cart->order ) {
				$cart->order->is_birthday = $is_birthday;
			}
			
			$_SESSION[ "cart" ] = $cart->to_assoc();
			CartView::cart_order_details_to_divs( $cart );
		} elseif ( $method == "save" ) {
			$order = $cart->order;
			
			$phone_number = isset( $_REQUEST[ "phoneNumber" ] ) ? StringUtils::convert( $_REQUEST[ "phoneNumber" ], 'string' ) : null;
			$errors .= validate_phone_number( $phone_number ); 	
			$customer_name = isset( $_REQUEST[ "customerName" ] ) ? StringUtils::convert( $_REQUEST[ "customerName" ], 'string' ) : null;
			$self_take = isset( $_REQUEST[ "selfTake" ] ) ? StringUtils::convert( $_REQUEST[ "selfTake" ], 'int' ) : null;
			$address = isset( $_REQUEST[ "address" ] ) ? StringUtils::convert( $_REQUEST[ "address" ], 'string' ) : null;
			
			if( $errors ) {
				echo "<div class='error'><h6>Ошибки при подтверждении заказа:</h6>".$errors."</div>";
			} else {
				$order->phone_number = $phone_number;
				$order->customer_name = $customer_name;
				$order->self_take = $self_take;
				$order->address = $address;
				
				$cart->order = Orders::save_order( $order );
				
				$_SESSION[ "cart" ] = $cart->to_assoc();
			}
		} elseif ( $method == "showDetails" ) {
			CartView::cart_order_details_to_divs( $cart );
		} elseif ( $method == "clear" ) {
			$cart = new Cart();
			$_SESSION[ "cart" ] = $cart->to_assoc();
			CartView::cart_order_details_to_divs( $cart );
		} elseif ( $method == "cart_order" ) {
			CartView::cart_order_page();
		}
	} else {
		
		CartView::cart_total_to_text( $cart );
	}
	
	function validate_phone_number( $phone_number) {
		if( $phone_number && strlen( $phone_number ) > 5 && strlen( $phone_number ) <= 11 ) {
			return null;
		} else {
			return "Некорректный номер телефона!";
		}
	}
?>