<?php
	require_once("db/connect.php");
	require_once("m-portion.php");
	
	class OrderDetail {
		
		public $id;
		public $portion_id;
		public $amount;
		public $cost; 
		
		public $order_id;
		public $portion;
		
		public static function create_from_assoc ( $fields ) {
			$order_detail = new OrderDetail( null, null, null, null );
			
			if( isset( $fields["id"] ) ) {
				$order_detail->id = $fields["id"];
			}
			
			if( isset( $fields["portion_id"] ) ) {
				$order_detail->portion_id = $fields["portion_id"];
			}
			
			if( isset( $fields["amount"] ) ) {
				$order_detail->amount = $fields["amount"];
			}
			
			if( isset( $fields["cost"] ) ) {
				$order_detail->cost = $fields["cost"];
			}
			
			if( isset( $fields["portion"] ) ) {
				$order_detail->portion = Portion::create_from_assoc( $fields["portion"] );
			}
			
			return $order_detail;
		}
		
		public function to_assoc() {
			$fields = array();
			
			$fields["id"] = $this->id;
			$fields["portion_id"] = $this->portion_id;
			$fields["amount"] = $this->amount;
			$fields["cost"] = $this->cost;
						
			if( isset( $this->portion ) ) {
				$fields["portion"] = $this->portion->to_assoc();
			}
			
			return $fields;
		}
		
		function __construct( $id, $portion_id, $amount, $cost ) {
			$this->id = $id;
			$this->portion_id = $portion_id;
			$this->amount = $amount;
			$this->cost = $cost; 
		}
		
		public function calculate_cost() {
			return $this->amount * $this->portion->price;
		}
		
		public function mysql_insert() {
			return "INSERT INTO order_detail( order_id, portion_id, amount, cost ) "
				.' VALUES ('
				.$this->order_id.', '
				.$this->portion->id.', '
				.$this->amount.', '
				.$this->calculate_cost().');'; 
		} 
	}
?>