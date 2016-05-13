<?php
	require_once("db/connect.php");
	require_once("m-order-detail.php");
	require_once("m-portion.php");
	
	class Order {
		public static $status_names = array( "Принят", "Отменен", "Готов", "Выполнен" ); 
		
		public $id;
		public $date_time;
		public $phone_number;
		public $customer_name;
		public $address;
		public $self_take; 
		public $status;
		public $is_birthday;
	
		public $details;
		public $discounts;
		public $gifst;
		
		public static function create_from_assoc ( $fields ) {
			$order = new Order( null, null, null, null, null, null );
			
			if( isset( $fields["id"] ) ) {
				$order->id =  $fields["id"];
			}
			
			if( isset( $fields["date_time"] ) ) {
				$order->date_time =  $fields["date_time"];
			}
			
			if( isset( $fields["phone_number"] ) ) {
				$order->phone_number =  $fields["phone_number"];
			}
			
			if( isset( $fields["customer_name"] ) ) {
				$order->customer_name =  $fields["customer_name"];
			}
			
			if( isset( $fields["address"] ) ) {
				$order->address =  $fields["address"];
			}
			
			if( isset( $fields["self_take"] ) ) {
				$order->self_take =  $fields["self_take"];
			}
			
			if( isset( $fields["is_birthday"] ) ) {
				$order->is_birthday =  $fields["is_birthday"];
			}
			
			if( isset( $fields["details"] ) ) {
				foreach( $fields["details"] as $detail ) {
					$order->details[] = OrderDetail::create_from_assoc( $detail );
				}
			}
			
			return $order;
		}
		
		public function to_assoc() {
			$fields = array();
			
			$fields["id"] = $this->id;
			$fields["date_time"] = $this->date_time;
			$fields["phone_number"] = $this->phone_number;
			$fields["customer_name"] = $this->customer_name;
			$fields["address"] = $this->address;
			$fields["self_take"] = $this->self_take;
			$fields["is_birthday"] = $this->is_birthday;
						
			if( isset( $this->details ) ) {
				foreach( $this->details as $detail ) {
					$fields["details"][] = $detail->to_assoc();
				}
			}
			
			return $fields;
		}
		
		function __construct( $id, $date_time, $phone_number, $customer_name, $address, $self_take ) {
			$this->id = $id;
			$this->date_time = $date_time;
			$this->phone_number = $phone_number;
			$this->customer_name = $customer_name;
			$this->address = $address;
			$this->self_take = $self_take;
			$this->details = array();
		}
		
		public function total_cost() {
			$total_cost = 0.0;
			
			if( isset( $this->details ) ) {
				foreach( $this->details as $detail ) {
					$total_cost += isset( $detail->cost ) ? $detail->cost : $detail->calculate_cost();
				}
			}
			
			return $total_cost;
		}
		
		public function discount_sum() {
			$total_cost = $this->total_cost();
			
			self::check_discounts_and_gifts( $this, $total_cost );
			
			$discount_value = 0;
			
			foreach( $this->discounts as $discount ) {
				$discount_value += $discount[ "value" ];
			}
			
			$discount_sum = $discount_value * $total_cost / 100.0;
			
			return $discount_sum;
		}
		
		public static function  check_discounts_and_gifts( Order $order, $total_cost ) {
			
			
			if( isset( $order ) && $total_cost ) {
				$order->discounts = array();
				
				if( $order->is_birthday ) {
					$order->discounts[ "birthday" ][ "descr" ] = 'Скидка в день рождения';
					$order->discounts[ "birthday" ][ "value" ] = 10;
				} else if( $order->self_take ) {
					$order->discounts[ "self_take" ][ "descr" ] = 'Забери сам - скидка';
					$order->discounts[ "self_take" ][ "value" ] = 10;
				} else {
					if( $total_cost >= 1000.0 && $total_cost < 2000 ) {
						$order->discounts[ "sum" ][ "value" ] = 5;
						$order->discounts[ "sum" ][ "descr" ] = 'Скидка за сумму заказа от 1000 руб.';
						$portion = Portions::get_portion_by_id( 9 ); // roll
					} elseif ( $total_cost >= 2000.0 && $total_cost < 3000 ) {
						$order->discounts[ "sum" ][ "value" ] = 7;
						$order->discounts[ "sum" ][ "descr" ] = 'Скидка за сумму заказа от 2000 руб.';
						$portion = Portions::get_portion_by_id( 10 ); // sok
					} elseif ( $total_cost >= 3000.0 && $total_cost < 5000 ) {
						$order->discounts[ "sum" ][ "value" ] = 10;
						$order->discounts[ "sum" ][ "descr" ] = 'Скидка за сумму заказа от 3000 руб.';
						$portion = Portions::get_portion_by_id( 12 ); // shampanskoe
					} elseif ( $total_cost >= 5000.0 ) {
						$order->discounts[ "sum" ][ "value" ] = 10;
						$order->discounts[ "sum" ][ "descr" ] = 'Скидка за сумму заказа от 5000 руб.';
						$portion = Portions::get_portion_by_id( 11 ); // discount card
					}
				}
				
				if( $portion ) {
					$order_detail = new OrderDetail( null, $portion->id, 1, null );
					$order_detail->portion = $portion;
					$order->gifts[ "sum" ] = $order_detail;
				}
			}
			
		}
		
		public static function check_need_renew( $cost_before, $cost_after ) {
			$totals = array( 1000, 2000, 3000, 5000 );
			
			foreach( $totals as $total ) {
				if( $total > $cost_before && $total <= $cost_after ||
					$total >= $cost_after && $total < $cost_before )
				{
					return true;
				}
			}
			
			return false;
		}
		
		public function date_time_format( $format ) {
			if ( is_string( $this->date_time ) ) {
				$date_time = new DateTime( $this->date_time );
				return $date_time->format( $format );
			} elseif( get_class( $this->date_time ) == 'DateTime' ) {
				return $this->date_time->format( $format ); 
			} 
		}
		
		public function print_available_actions() {
			$html = "";
			
			if( $this->status != 1 && $this->status != 3 ) {
				for( $i = 1; $i < count( self::$status_names ); $i++ ) {
					if( $this->status != $i ) {
						$html .= "<a href='../core/c-order.php?method=stateChange&status=".$i
							."&id=".$this->id."' class='st-change'>".self::$status_names[ $i ]."</a><br>";
					}
				}
			}
			
			return $html;
		}
		
		public function mysql_insert() {
			return "INSERT INTO `order`(date_time, phone_number, customer_name, address, self_take, is_birthday) VALUES ("
					."NOW(), "
					."'".$this->phone_number."', "
					."'".$this->customer_name."', "
					."'".$this->address."', "
					.$this->self_take.", "
					.$this->is_birthday
					."); ";
		}
		
		public function status_name() {
			return self::$status_names[ $this->status ];
		}
	}
	
	class Orders {
		
		public static function get_orders_in_period( $date_from, $date_to, $statuses, $phone_number ) {
			return self::get_orders_by_all( $date_from, $date_to, $statuses, null, $phone_number );
		}
		
		public static function get_order_by_id( $id ) {
			$orders = self::get_orders_by_all( null, null, null, $id, null );
			
			if ( $orders != null && count( $orders ) > 0 ) {
				foreach($orders as $order ) return $order;
			}
			
			return null;
		}
		
		public static function get_orders_by_all( $date_from, $date_to, $statuses, $ids, $phone ) {
			$orders = array();
			
			
			$date_part = null;
			
			if( $date_from && $date_to ) {
				$date_part = "o.date_time  BETWEEN '".$date_from."' AND '".$date_to."'";
			} elseif( $date_from ) {
				$date_part = "o.date_time  >= '".$date_from."'";
			} elseif( $date_to ) {
				$date_part = "o.date_time  <= '".$date_to."'";
			}

			$status_part = ( $statuses != null ? " o.`status` IN (".$statuses.") " : null );
			
			
			
			$ids_part = ( $ids != null ? " o.`id` IN (".$ids.")" : null );
			
			if( $phone ) $phone = DB::$mysqli->real_escape_string( $phone );
			$phone_part = ( $phone != null ? " o.`phone_number` like '%".$phone."%'" : null );			
			
			$parts = array( $date_part, $status_part, $ids_part, $phone_part );
			
			foreach( $parts as $part ) {
				if( $part != null ) {
					$pieces[] = $part; 
				}
			}
			
			if( isset( $pieces ) ) 
				$where_part = implode( " AND ", $pieces );
			else
				$where_part = null; 
				
			if( $where_part ) {
				$where_part = " WHERE ".$where_part;
			}
			
			$query = "SELECT "
				."o.`id` AS `order_id`, o.`date_time`, o.`phone_number`, o.`customer_name`, o.`address`, o.`self_take`, o.`status`, o.`is_birthday`, "
				." od.`id` AS `od_id`, od.`portion_id`, od.`amount` AS `od_amount`, od.`cost`, "
				." p.`good_id`, p.`amount` AS `p_amount`, p.`gramms`, p.`milliliters`, p.`price`, p.`menu_visible`, "
				." g.`category_id`, g.`name` AS g_name, g.`description` AS g_description, g.`sort_index`, g.`menu_visible`, g.`kcal_per_100g`, g.`image_url` AS g_image_url"
				." FROM `order` o "
				." JOIN `order_detail` od ON od.order_id = o.id"
				." JOIN `portion` p ON p.id = od.portion_id "
				." JOIN `good` g ON g.id = p.good_id "
				.$where_part." LIMIT 0,1000; ";
			
			$result = DB::$mysqli->query( $query );
			
			if( DB::$mysqli->error ) {
				print_r( DB::$mysqli->error );
				print_r( $query );
				return null;
			}
			
			
			while( $row = $result->fetch_assoc() ) {
				if( !isset( $orders[ $row[ "order_id" ] ] ) ) {
					$order = 
						new Order( $row[ "order_id" ], $row[ "date_time" ], 
						$row[ "phone_number" ], $row[ "customer_name" ], 
						$row[ "address" ], $row[ "self_take" ] );
						
					$order->status = $row[ "status" ];
					$order->is_birthday = $row[ "is_birthday" ];
						
					$orders[ $row[ "order_id" ] ] = $order;
					
					
				} else {
					$order = $orders[ $row[ "order_id" ] ];
				}
				
				$order_detail = new OrderDetail( $row[ "od_id" ], $row[ "portion_id" ], $row[ "od_amount" ], 
					$row[ "cost" ] );
				
				$portion = new Portion( $row[ "portion_id" ], $row[ "good_id" ], $row[ "p_amount" ], 
					$row[ "gramms" ], $row[ "milliliters" ], $row[ "price" ] );
				
				$good = new Good( $row[ "good_id" ], $row[ "category_id" ], $row[ "g_name" ], 
					$row[ "g_image_url" ], $row[ "g_description" ], $row[ "kcal_per_100g" ] );
				
				$portion->good = $good;
				
				$order_detail->portion = $portion; 
				
				$order->details[] = $order_detail;
			}
			
			return $orders;
		}
		
		public static function save_order( Order $order ) {		
			
			//date_default_timezone_set("Etc/GMT+3");
			//$order->date_time = date("Y-m-d H:i:s");
			
			if( isset( $order ) && isset( $order->details ) && count( $order->details ) > 0 ) {
				
				$query = $order->mysql_insert();
					
				DB::$mysqli->query( $query );
				
				if( DB::$mysqli->error ) {
					print_r( DB::$mysqli->error );
				}
				
				$id = DB::$mysqli->insert_id;
				$order->id = $id;
				
				foreach( $order->details as $detail ) {
					
					$detail->order_id = $id;
					
					$query = "";
					$query .= $detail->mysql_insert();
					
					DB::$mysqli->query( $query );
				
					if( DB::$mysqli->error ) {
						print_r( DB::$mysqli->error );
					}
				}
				
				
			} else {
				echo "Ошибка сохранения заказа! Заказ пуст!";
			}
			
			return $order;
		}
		
		public static function update_status( $order_id, $status ) {
			$query = "UPDATE `order` SET `status` = ".$status." WHERE `id` = ".$order_id.";";
			
			DB::$mysqli->query( $query );
			
			if( DB::$mysqli->error ) {
				echo "<br>";
				print_r( DB::$mysqli->error );
			}
		}
	}
	
	
?>