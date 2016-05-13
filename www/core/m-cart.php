<?php
	require_once("m-category.php");
	require_once("m-order.php");
	
	class Cart {
		public $order; 
		
		public static function create_from_assoc ( $fields ) {
			$cart = new Cart();
			
			if( isset( $fields["order"] ) ) {
				$cart->order = Order::create_from_assoc( $fields["order"] );
			}
			
			return $cart;
		}
		
		
		public function to_assoc() {
			$fields = array();
			
			$fields["order"] = $this->order->to_assoc();
			
			return $fields;
		}
		
		function __construct() {
			$this->order = new Order( null, null, null, null, null, null );
		}
		
		public function update_amount_by_portion_id ($portion_id, $amount ) {
			$order_detail = null;
			if( isset( $this->order ) && isset( $this->order->details ) ) {
				foreach( $this->order->details as $detail ) {
					if( isset( $detail->portion_id ) && $detail->portion_id == $portion_id ) {
						$detail->amount = $detail->amount + $amount;
						$order_detail = $detail;
					}
				}
			}
			return $order_detail;
		}
		
		public function delete_by_portion_id ( $portion_id ) {
			$new_details = null;
			if( isset( $this->order ) && isset( $this->order->details ) ) {
				foreach( $this->order->details as $detail ) {
					if( isset( $detail->portion_id ) && $detail->portion_id == $portion_id ) {
						;
					} else {
						$new_details[] = $detail;
					}
				}
				
				$this->order->details = $new_details;
			}
		}
		
		public function add_order_details( $order_details ) {
			$this->order->details[] = $order_details;
		}
	}
?>