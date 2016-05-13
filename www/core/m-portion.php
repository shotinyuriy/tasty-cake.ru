<?php
	require_once("m-good.php");
	
	class Portion {
		public $id;
		public $good_id;
		public $amount;
		public $gramms;
		public $milliliters;
		public $price;
		public $menu_visible;
		
		public $good;
		
		public static function create_from_assoc ( $fields ) {
			$portion = new Portion( null, null, null, null, null, null );
			
			if( isset( $fields["id"] ) ) {
				$portion->id = $fields["id"];
			}
			
			if( isset( $fields["good_id"] ) ) {
				$portion->good_id = $fields["good_id"];
			}
			
			if( isset( $fields["amount"] ) ) {
				$portion->amount = $fields["amount"];
			}
			
			if( isset( $fields["gramms"] ) ) {
				$portion->gramms = $fields["gramms"];
			}
			
			if( isset( $fields["milliliters"] ) ) {
				$portion->milliliters = $fields["milliliters"];
			}
			
			if( isset( $fields["price"] ) ) {
				$portion->price = $fields["price"];
			}
			
			if( isset( $fields["good"] ) ) {
				$portion->good = Good::create_from_assoc( $fields["good"] ); 
			}
			
			return $portion;
		}
		
		public function to_assoc() {
			$fields = array();
			
			$fields["id"] = $this->id;
			$fields["good_id"] = $this->good_id;
			$fields["amount"] = $this->amount;
			$fields["gramms"] = $this->gramms;
			$fields["milliliters"] = $this->milliliters;
			$fields["price"] = $this->price;
			
			if( isset( $this->good ) ) {
				$fields["good"] = $this->good->to_assoc();
			}
			
			return $fields;
		}
		
		function __construct($id, $good_id, $amount, $gramms, $milliliters, $price ) {
			$this->id = $id;
			$this->good_id = $good_id;
			$this->amount = $amount;
			$this->gramms = $gramms;
			$this->milliliters = $milliliters;
			$this->price = $price;
		}
		
		
		public function mysql_insert() {
			return "INSERT INTO `portion`( good_id, amount, gramms, milliliters, price ) VALUES ("
					."'".$this->good_id."', "
					.$this->amount.", "
					.$this->gramms.", "
					.$this->milliliters.", "
					.$this->price."); ";
		}
		
		public function mysql_update() {
			if( !isset( $this->id ) || !isset($this->good_id) ) {
				return "";
			}
						
			return "UPDATE `portion` SET "
			."good_id  = '".$this->good_id ."', "
			."amount = ".$this->amount.", "
			."gramms = ".$this->gramms.", "
			."milliliters = '".$this->milliliters."', "
			."price = ".$this->price
			." WHERE id = ".$this->id.";";
			
		}
	}
	
	class Portions {
		
		public static function fill_portions_of_goods( $goods ) {
			$good_ids = "";
				
			if( isset( $goods ) ) {
				foreach( $goods as $good ) {
					$good_ids .= strlen( $good_ids ) > 0 ? ",\"".$good->id."\"" : "\"".$good->id."\"";  
				}
			}
			
			$query = "SELECT p.`id`, p.`good_id`, p.`amount`, p.`gramms`, p.`milliliters`, p.`price`, p.`menu_visible` FROM portion p WHERE p.`good_id` IN ("
			.$good_ids.") AND menu_visible = 1 ORDER BY p.`good_id`, p.`price`;";
						
			$result = DB::$mysqli->query( $query );
				
			while( $row = $result->fetch_assoc() ) {
				$good_id = $row[ "good_id" ];
				
				if( strlen( $good_id ) > 0 ) {
					$goods[ $good_id ]->portions[] = new Portion( $row[ "id" ], $good_id, $row[ "amount" ], $row[ "gramms" ], $row[ "milliliters" ], $row[ "price" ] );
				}
			}
			
			return $goods;
		}
		
		public static function get_portion_by_id( $id ) {
			$query = "SELECT p.`good_id`, p.`amount`, p.`gramms`, p.`milliliters`, p.`price`, p.`menu_visible` FROM portion p WHERE p.`id`="
			.$id.";";
			
			$result = DB::$mysqli->query( $query );
			
			$portion = null;
			while( $row = $result->fetch_assoc() ) {
				$portion = new Portion( $id, $row[ "good_id" ], $row[ "amount" ], $row[ "gramms" ], $row[ "milliliters" ], $row[ "price" ] );
				
				$good = Goods::get_good_by_id( $row[ "good_id" ] );
				
				$portion->good = $good;
			}
			
			return $portion;
		}
		
		public static function save_portion( $portion ) {
						if( isset( $portion ) ) {
				$query = "";
				if( isset( $portion->id ) && strlen( $portion->id ) > 0 ) {
					$query = $portion->mysql_update(); 
				} else {
					$query = $portion->mysql_insert();
				}
				
				DB::$mysqli->query( $query );
				
				if( DB::$mysqli->error ) {
					echo $query."<br/>";
					print_r( DB::$mysqli->error );
					return null;
				}
				
				if( $portion->id == null ) {
					$id = DB::$mysqli->insert_id;
					$portion->id = $id;
				}
				
				return $portion;
			} else {
				echo "Ошибка при сохранении порции! Порция не указана!";
				return null;
			}
		}
	}
?>