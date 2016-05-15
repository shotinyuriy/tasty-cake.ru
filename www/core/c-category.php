<?php
	session_start();
	require_once("m-category.php");
	require_once("v-category.php");
	require_once("util.php");
	
	$categories = null;
	
	if( isset( $_REQUEST["method"] ) ) {
		$method = StringUtils::convert( $_REQUEST["method"], 'string' );
		
		if( $method == 'save' ) {
			save();
		} elseif ( $method == 'edit' ) {
			edit();
		} elseif ( $method == 'delete' ) {
			delete();
		} elseif ( $method == 'cms' ) {
			cms();
		}
	} else {
			
		if( isset( $_REQUEST["view"] ) ) {
			$view = StringUtils::convert( $_REQUEST["view"], 'string' );
			
			$current_category_id = "";
			
			if( isset( $_SESSION["category_id"] ) ) {
				$current_category_id = StringUtils::convert( $_SESSION["category_id"], 'string' );
			}
			
			$cms = false;
			if( isset( $_REQUEST[ 'cms' ] ) && $_REQUEST[ 'cms' ] == true ) {
				$cms = true;
				$categories = Categories::get_all_categories();
			} else {
				$categories = Categories::get_all_categories( 1 );
			}
			
			if( $view == "list" ) {
				CategoryView::category_to_li( $categories, $current_category_id, $cms );
			}
		} else {
			$categories = Categories::get_main_categories();
			CategoryView::category_to_div( $categories );
		}
	}
	
	function save() {
		include("cms-result-header.php");
		
		$id = isset( $_POST[ "id" ] ) ? StringUtils::convert( $_POST[ "id" ], 'string' ) : null;
		$parent_category_id = isset( $_POST[ "parent_category_id" ] ) ? StringUtils::convert( $_POST[ "parent_category_id" ], 'string' ) : null;
		$name =	isset( $_POST[ "name" ] ) ? StringUtils::convert( $_POST[ "name" ], 'string' ) : null;
		$stored_image_url = isset( $_POST[ "stored_image_url" ] ) ? StringUtils::convert( $_POST[ "stored_image_url" ], 'string' ) : null;
		$image_url = isset( $_POST[ "image_url" ] ) ? StringUtils::convert( $_POST[ "image_url" ], 'string' ) : null;
		$menu_visible = isset( $_POST[ "menu_visible" ] ) ? StringUtils::convert( $_POST[ "menu_visible" ], 'int' ) : null;
		
		$category = new Category( $id, $name, $image_url );
		$category->parent_category_id = $parent_category_id;
		$category->menu_visible = $menu_visible;
			
		$id = $category->generate_id();
		
		if( $category != null && $id != null ) {
			$category->image_url = FileUtils::saveFile( $id );
		}
		
		if( $category->image_url == null ) {
			$category->image_url = $stored_image_url;
		}
		
		$category = Categories::save_category( $category );
		
		include("cms-result-footer.php");
	}
	
	function edit() {
		
		$id = isset( $_REQUEST[ "id" ] ) ?  StringUtils::convert( $_REQUEST[ "id" ], 'string' ) : null;
		
		if( $id != null ) {
			$category = Categories::get_category_by_id( $id );
		}
		
		if( $category == null ) {
			$category = new Category( null, null, null );
			
			$parent_category_id = get_category_id();
			$category->parent_category_id = $parent_category_id;
		}
		
		CategoryView::edit_form( $category );
	}
	
	function delete() {
		if ($_SESSION["role"] == User::ADMINISTRATOR) {
			if (isset($_REQUEST["id"])) {
		        $id = StringUtils::convert($_REQUEST["id"], 'string');
				if($id != null) {
					if(isset($_REQUEST["confirm"]) && $_REQUEST["confirm"]=="yes") {
						Categories::delete($id);
					} else {
						$category = Categories::get_category_by_id($id);
						CategoryView::confirm_delete($category);
					}
				}
		    }
		}
	}
	
	function cms() {
		if ($_SESSION["role"] == User::ADMINISTRATOR) {
		    $categories = Categories::get_all_categories();
		    $current_category_id = "";
		
		    if (isset($_SESSION["category_id"])) {
		        $current_category_id = StringUtils::convert($_SESSION["category_id"], 'string');
		    }
		}
		CategoryView::cms_form($categories, $current_category_id, true);
	}
	
	function get_category_id() {
		$category_id = null;
		
		if( isset( $_REQUEST["category_id"] ) ) {
			
			$category_id = StringUtils::convert( $_REQUEST["category_id"], 'string' );
		} /* elseif ( isset( $_SESSION["category_id"] ) ) {
			$category_id = $_SESSION["category_id"];
		}*/
		
		return $category_id;
	}
?>