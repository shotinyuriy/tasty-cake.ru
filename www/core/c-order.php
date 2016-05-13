<?php
session_start();
require_once("m-order-detail.php");
require_once("m-order.php");
require_once("m-portion.php");
require_once("v-order.php");
require_once("util.php");
	
date_default_timezone_set("Etc/GMT+3");
	
if( isset( $_REQUEST["method"] ) ) {
	$method = StringUtils::convert( $_REQUEST["method"], 'string' );
	
	if( $method == 'save' ) {
		save();
	} elseif( $method == 'stateChange' ) {
		state_change();
	} elseif( $method == 'filterForm' ) {
		OrderView::filter_form();
	} elseif( $method == 'searchOrders' ) {
		show_orders_in_period();
	} elseif( $method == 'details' ) {
		show_order_details();
	}
}


function show_orders_in_period() {
	$date_from = isset( $_REQUEST[ "dateFrom" ] ) ? StringUtils::convert( $_REQUEST[ "dateFrom" ], 'string' ) : null; 
	$date_to = isset( $_REQUEST[ "dateTo" ] ) ? StringUtils::convert( $_REQUEST[ "dateTo" ], 'string' ) : null;
	$statuses = isset( $_REQUEST[ "statuses" ] ) ? StringUtils::convert( $_REQUEST[ "statuses" ], 'string' ) : null;
	$phone_number = isset( $_REQUEST[ "phoneNumber" ] ) ? StringUtils::convert( $_REQUEST[ "phoneNumber" ], 'string' ) : null;
	
	$orders = Orders::get_orders_in_period( $date_from, $date_to, $statuses, $phone_number );

	OrderView::orders_to_table( $orders );
}

function state_change() {
	$id = isset( $_REQUEST[ "id" ] ) ? StringUtils::convert( $_REQUEST[ "id" ], 'int' ) : null;
	$status = isset( $_REQUEST[ "status" ] ) ? StringUtils::convert( $_REQUEST[ "status" ], 'int' ) : null;
	
	Orders::update_status( $id, $status );
}

function show_order_details() {
	$id = isset( $_REQUEST[ "id" ] ) ? StringUtils::convert( $_REQUEST[ "id" ], 'int' ) : null;
	
	$order = Orders::get_order_by_id( $id );
	
	OrderView::order_to_details( $order, true );
}

?>