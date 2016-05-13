<?php
	require_once("db/connect.php");
	
	class Category {
		public $id;
		public $name;
		public $parent_category_id;
		public $parent_category;
		public $sort_index;
		public $menu_visible;
		public $image_url;
		public $sub_categories;
		
		public static function create_from_assoc ( $fields ) {
				$category = new Category( null, null, null );
				
				if( isset( $fields["id"] ) ) {
					$category->id = $fields["id"];
				}

				if( isset( $fields["name"] ) ) {
					$category->name = $fields["name"];
				}
				
				if( isset( $fields["image_url"] ) ) {
					$category->image_url = $fields["image_url"];
				}
				
				if( isset( $fields["parent_category"] ) ) {
					$category->parent_category = Category::create_from_assoc( $fields["parent_category"] ); 
				}
				
				return $category;
		}
		
		public function to_assoc() {
			$fields = array();
			
			$fields["id"] = $this->id;
			$fields["name"] = $this->name;
			$fields["image_url"] = $this->image_url;
			if( isset( $this->parent_category ) ) {
				$fields["parent_category"] = $this->parent_category->to_assoc();
			}
			
			return $fields;
		}
		
		function __construct( $id, $name, $image_url ) {
			$this->id = $id;
			$this->name = $name;
			$this->image_url = $image_url;
			$this->parent_category_id = null;
		}
		
		public function generate_id() {
			
			if( $this->id != null ) {
				return $this->id;
			}
			
			if( isset( $this->name ) ) {
				$id = StringUtils::str2id( $this->name );
				return $id;
			}
			
			return null;
		}
		
		public function mysql_insert() {
			if( isset( $this->name ) ) {
				$this->id = StringUtils::str2id( $this->name );
			}
			
			if( !isset( $this->menu_visible ) || ( $this->menu_visible != 0 && $this->menu_visible !=1 ) ) {
				$this->menu_visible = 0;
			}
			
			$parent_category_id = 
				$this->parent_category_id != null && strlen( $this->parent_category_id ) > 0 ?
					"'".$this->parent_category_id."'" : 'NULL';
			
			$image_url = 
				isset( $this->image_url ) && strlen( $this->image_url ) > 0 ?
					"'".$this->image_url."'" : 'NULL';
			
			return "INSERT INTO `category`(id, parent_category_id, name, image_url, menu_visible, sort_index) VALUES("
			."'".$this->id."', "
			.$parent_category_id.", "
			."'".$this->name."', "
			.$image_url.", "
			.$this->menu_visible.", "
			.$this->sort_index
			.")";
		}
		
		public function mysql_update() {
			if( !isset( $this->id ) ) {
				return "";
			}
			
			if( !isset( $this->menu_visible ) || ( $this->menu_visible != 0 && $this->menu_visible !=1 ) ) {
				$this->menu_visible = 0;
			}
			
			$parent_category_id = 
				isset( $this->parent_category_id ) && strlen( $this->parent_category_id ) > 0 ?
					"'".$this->parent_category_id."'" : 'NULL';
			
			$image_url = 
				isset( $this->image_url ) && strlen( $this->image_url ) > 0 ?
					"'".$this->image_url."'" : 'NULL';
			
			return "UPDATE `category` SET "
			."parent_category_id = ".$parent_category_id.", "
			."name = '".$this->name."', "
			."image_url = ".$image_url.", "
			."menu_visible = ".$this->menu_visible
			." WHERE id = '".$this->id."';";
			
		}
	}
	
	class Categories {
	
		public static function get_category_by_id( $id ) {
			$query = "SELECT id, `parent_category_id`, `name`, `image_url`, `sort_index`, `menu_visible` "
			." FROM `category` WHERE id = '".$id."' ORDER BY sort_index LIMIT 0, 1000;";
			$result = DB::$mysqli->query( $query );
			$category = null;
			
			if( isset( $result ) && $result != null ) {
				while( $row = $result->fetch_assoc() ) {
					$category = new Category( $row[ "id" ], $row[ "name" ], $row[ "image_url" ] );
					$category->parent_category_id = $row[ "parent_category_id" ];
					$category->menu_visible = $row[ "menu_visible" ];
					$category->sort_index = $row[ "sort_index" ];
				}
			}
			
			if( DB::$mysqli->error ) {
				print_r( DB::$mysqli->error );
				echo $query;
				return null;
			}
			
			return $category;
		}
	
		public static function get_main_categories() {
			
			$query = "SELECT id, `name`, image_url FROM `category` WHERE parent_category_id IS NULL AND menu_visible = 1 ORDER BY sort_index LIMIT 0, 1000;";
			$result = DB::$mysqli->query( $query );
			$categories = null;
			
			if( isset( $result ) && $result != null ) {
				while( $row = $result->fetch_assoc() ) {
					$categories[ $row[ "id" ] ] = new Category( $row[ "id" ], $row[ "name" ], $row[ "image_url" ] );
				}
			} 
			
			if( DB::$mysqli->error ) {
				print_r( DB::$mysqli->error );
				echo $query;
				return null;
			}
			
			return $categories;
		}
		
		public static function search_categories_by_text( $text ) {
			$categories = null;
			
			if( $text ) {
				$text = strtolower( $text );
				$text = DB::$mysqli->real_escape_string( $text );
				
				$query = "SELECT id, `name`, image_url FROM `category` WHERE menu_visible = 1 "
					."AND LOWER(`name`) like '%".$text."%' "
					."ORDER BY sort_index LIMIT 0, 1000;";	
				
				$result = DB::$mysqli->query( $query );
				
				if( isset( $result ) && $result != null ) {
					while( $row = $result->fetch_assoc() ) {
						$categories[ $row[ "id" ] ] = new Category( $row[ "id" ], $row[ "name" ], $row[ "image_url" ] );
					}
				} 
				
				if( DB::$mysqli->error ) {
					print_r( DB::$mysqli->error );
					echo $query;
					return null;
				}
			}
			
			return $categories;
		}
	
		public static function get_all_categories( $menu_visible = -1 ) {
				
			$where_part = $menu_visible >= 0 ? " WHERE menu_visible = ".$menu_visible : "";
			
			$query = "SELECT id, `name`, image_url, parent_category_id, `menu_visible` FROM `category` ".$where_part
				." ORDER BY COALESCE(parent_category_id, ''), sort_index LIMIT 0, 1000;";
			
			$result = DB::$mysqli->query( $query );
			$categories = null;
			
			if( isset( $result ) && $result != null ) {
				while( $row = $result->fetch_assoc() ) {
					$category = new Category( $row[ "id" ], $row[ "name" ], $row[ "image_url" ] );
					$category->parent_category_id = $row[ "parent_category_id" ];
					$category->menu_visible = $row[ "menu_visible" ];
					
					$categories[ $row[ "id" ] ] = $category;
				}
			} 
			
			if( DB::$mysqli->error ) {
				print_r( DB::$mysqli->error );
				echo $query;
				return null;
			} else {
				foreach( $categories as $category ) {
					if( $category->parent_category_id ) {
						$categories[ $category->parent_category_id ]->sub_categories[ $category->id ] = $category;
					} else {
						$final_categories[ $category->id ] = $category;
					}
				}
				
				$categories = $final_categories;
			}
			
			return $categories;
		}
		
		public static function get_sub_categories( $id ) {
			$query = "SELECT id, `name`, image_url FROM `category` WHERE parent_category_id ='".$id
			."' AND menu_visible = 1 ORDER BY sort_index LIMIT 0, 1000;";
			
			$result = DB::$mysqli->query( $query );
			$categories = null;
			
			if( isset( $result ) && $result != null ) {
				while( $row = $result->fetch_assoc() ) {
					$categories[ $row[ "id" ] ] = new Category( $row[ "id" ], $row[ "name" ], $row[ "image_url" ] );
				}
			} else {
				$categories = null;
			}
			
			return $categories;
		}
		
		public static function save_category( Category $category ) {
			if( isset( $category ) ) {
				$query = "";
				if( isset( $category->id ) && strlen( $category->id ) > 0 ) {
					$query = $category->mysql_update(); 
				} else {
					$category->sort_index = self::get_last_sort_index() + 1;
					
					$query = $category->mysql_insert();
				}
				
				DB::$mysqli->query( $query );
				
				if( DB::$mysqli->error ) {
					print_r( DB::$mysqli->error );
					echo $query;
					return null;
				}
				
				return $category;
			} else {
				echo "Ошибка при сохранении категории! Категория не указана!";
				return null;
			}
		}

		public static function get_last_sort_index() {
				$query = "SELECT MAX(`sort_index`) as `sort_index` FROM `category`";
				
				$result = DB::$mysqli->query( $query );
				
				if( DB::$mysqli->error ) {
					print_r( DB::$mysqli->error );
					echo $query;
					return null;
				}
				
				while( $row =  $result->fetch_assoc() ) {
					$last_sort_index = $row[ "sort_index" ];
				}
				
				return $last_sort_index;
		}
	}
?>