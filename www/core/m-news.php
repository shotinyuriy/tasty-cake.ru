<?php
require_once("db/connect.php");
require_once("util.php");

class News
{
    public $id;
    public $image_url;
    public $title;
    public $description;
    public $menu_visible;

    public function mysql_insert() {

        if( !isset( $this->id ) ) {
            $this->id = NewsList::generate_id();
        }

        if( !isset( $this->menu_visible ) || ( $this->menu_visible != 0 && $this->menu_visible !=1 ) ) {
            $this->menu_visible = 0;
        }

        $image_url =
            isset( $this->image_url ) && strlen( $this->image_url ) > 0 ?
                "'".$this->image_url."'" : 'NULL';

        return "INSERT INTO `news`(id, title, image_url, description, menu_visible) VALUES("
        ."'".$this->id."', "
        ."'".$this->title."', "
        .$image_url.", "
        ."'".$this->description."', "
        .$this->menu_visible
        .")";
    }

    public function mysql_update() {
        if( !isset( $this->id ) ) {
            return "";
        }

        if( !isset( $this->menu_visible ) || ( $this->menu_visible != 0 && $this->menu_visible !=1 ) ) {
            $this->menu_visible = 0;
        }

        $image_url =
            isset( $this->image_url ) && strlen( $this->image_url ) > 0 ?
                "'".$this->image_url."'" : 'NULL';

        return "UPDATE `news` SET "
        ."title = '".$this->title."', "
        ."image_url = ".$image_url.", "
        ."description = '".$this->description."', "
        ."menu_visible = ".$this->menu_visible
        ." WHERE id = '".$this->id."';";
    }
}

class NewsList
{

    public static function get_all_news($menu_visible = null)
    {
        $news_list = array();

        $menu_visible_query = isset($menu_visible) && !is_null($menu_visible)? " n.`menu_visible` = '$menu_visible' " : " n.`menu_visible` IN (0,1)";

        $query = "SELECT "
            ."n.`id`, n.`title`, n.`image_url`, n.`description`, n.`menu_visible` "
            ."FROM `news` n "
            ."WHERE $menu_visible_query ORDER BY n.`id`;";

        $result = DB::$mysqli->query( $query );

        while( $row = $result->fetch_assoc() ) {
            $news = new News();
            $news->id = $row[ "id" ];
            $news->title = $row[ "title" ];
            $news->image_url = $row[ "image_url" ];
            $news->description = $row[ "description" ];
            $news->menu_visible = $row[ "menu_visible" ];

            $news_list[ $row[ "id" ] ] = $news;
        }

        return $news_list;
    }

    public static function generate_id() {
        $query = "SELECT MAX(`id`+1) as `id` FROM `news`";

        $result = DB::$mysqli->query( $query );

        if( DB::$mysqli->error ) {
            print_r( DB::$mysqli->error );
            echo $query;
            return 0;
        }

        while( $row =  $result->fetch_assoc() ) {
            $last_id = $row[ "id" ];
        }

        if(!isset($last_id) || $last_id == null) {
            $last_id = 1;
        }
        return $last_id;
    }

    public static function save_news(News $news ) {
        if( isset( $news ) ) {
            $query = "";
            if( isset( $news->id ) && strlen( $news->id ) > 0 ) {
                $query = $news->mysql_update();
            } else {
                $query = $news->mysql_insert();
            }

            DB::$mysqli->query( $query );

            if( DB::$mysqli->error ) {
                print_r( DB::$mysqli->error );
                return null;
            }

            return $news;
        } else {
            echo "Ошибка при сохранении новости! Новость не указана!";
            return null;
        }
    }

    public static function get_news_by_id( $id ) {
        if( isset( $id ) ) {
            $query = "SELECT `id`, `title`, `description`, `image_url`, `menu_visible` FROM `news` WHERE `id` = $id";

            $result = DB::$mysqli->query( $query );

            if( DB::$mysqli->error ) {
                print_r( DB::$mysqli->error );
                echo $query;
                return 0;
            }

            $news = null;
            while( $row =  $result->fetch_assoc() ) {
                $news = new News();
                $news->id = $row[ "id" ];
                $news->title = $row[ "title" ];
                $news->description = $row[ "description" ];
                $news->image_url = $row["image_url"];
                $news->menu_visible = $row["menu_visible"];
            }

            return $news;
        } else {
            echo "Ошибка при сохранении новости! Новость не указана!";
            return null;
        }
    }
}

?>