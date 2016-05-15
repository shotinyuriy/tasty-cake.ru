<?php
require_once("m-category.php");
require_once("util.php");

class Good
{
    public $id;
    public $category_id;
    public $name;
    public $description;
    public $sort_index;
    public $menu_visible;
    public $image_url;
    public $kcal_per_100g; // Количество ккал на 100г

    public $category;
    public $portions; // Массив порций блюда / напитка

    public static function create_from_assoc($fields)
    {
        $good = new Good(null, null, null, null, null, null);

        if (isset($fields["id"])) {
            $good->id = $fields["id"];
        }

        if (isset($fields["category_id"])) {
            $good->category_id = $fields["category_id"];
        }

        if (isset($fields["name"])) {
            $good->name = $fields["name"];
        }

        if (isset($fields["image_url"])) {
            $good->image_url = $fields["image_url"];
        }

        if (isset($fields["description"])) {
            $good->description = $fields["description"];
        }

        if (isset($fields["kcal_per_100g"])) {
            $good->kcal_per_100g = $fields["kcal_per_100g"];
        }

        if (isset($fields["category"])) {
            $good->category = Category::create_from_assoc($fields["category"]);
        }

        return $good;
    }

    public function to_assoc()
    {
        $fields = array();

        $fields["id"] = $this->id;
        $fields["category_id"] = $this->category_id;
        $fields["name"] = $this->name;
        $fields["image_url"] = $this->image_url;
        $fields["description"] = $this->description;
        $fields["kcal_per_100g"] = $this->kcal_per_100g;

        if (isset($this->category)) {
            $fields["category"] = $this->category->to_assoc();
        }

        return $fields;
    }

    function __construct($id, $category_id, $name, $image_url, $description, $kcal_per_100g)
    {
        $this->id = $id;
        $this->category_id = $category_id;
        $this->name = $name;
        $this->image_url = $image_url;
        $this->description = $description;
        $this->kcal_per_100g = $kcal_per_100g;
    }

    function generate_id()
    {

        if ($this->id != null) {
            return $this->id;
        }

        if (isset($this->name)) {
            $id = StringUtils::str2id($this->name);
            $id = $this->category_id . "_" . $id;

            return $id;
        }

        return null;
    }

    public function mysql_insert()
    {

        if (isset($this->name)) {
            $this->id = $this->generate_id();
        }

        if (!isset($this->menu_visible) || ($this->menu_visible != 0 && $this->menu_visible != 1)) {
            $this->menu_visible = 0;
        }

        $image_url =
            isset($this->image_url) && strlen($this->image_url) > 0 ?
                "'" . $this->image_url . "'" : 'NULL';

        return "INSERT INTO `good`(id, category_id, name, image_url, description, kcal_per_100g, menu_visible, sort_index) VALUES("
        . "'" . $this->id . "', "
        . "'" . $this->category_id . "', "
        . "'" . $this->name . "', "
        . $image_url . ", "
        . "'" . $this->description . "', "
        . $this->kcal_per_100g . ", "
        . $this->menu_visible . ", "
        . $this->sort_index
        . ")";
    }

    public function mysql_update()
    {
        if (!isset($this->id)) {
            return "";
        }

        if (!isset($this->menu_visible) || ($this->menu_visible != 0 && $this->menu_visible != 1)) {
            $this->menu_visible = 0;
        }

        $category_id =
            isset($this->category_id) && strlen($this->category_id) > 0 ?
                "'" . $this->category_id . "'" : 'NULL';

        $image_url =
            isset($this->image_url) && strlen($this->image_url) > 0 ?
                "'" . $this->image_url . "'" : 'NULL';

        return "UPDATE `good` SET "
        . "category_id  = " . $category_id . ", "
        . "name = '" . $this->name . "', "
        . "image_url = " . $image_url . ", "
        . "description = '" . $this->description . "', "
        . "menu_visible = " . $this->menu_visible . ", "
        . "kcal_per_100g = " . $this->kcal_per_100g
        . " WHERE id = '" . $this->id . "';";

    }

    public function get_first_portion()
    {
        if (isset($this->portions) && count($this->portions) > 0) {
            return $this->portions[0];
        }

        return null;
    }
}

class Goods
{
    private static $goods;

    public static function search_goods_by_text($text)
    {
        $goods = null;

        if ($text) {
            $text = strtolower($text);
            $text = DB::$mysqli->real_escape_string($text);

            $query = "SELECT "
                . "g.`id`, g.`category_id`, g.`name`, g.`image_url`, g.`description`, g.`kcal_per_100g`, g.`menu_visible`, c.`name` AS c_name, c.`image_url` AS c_image_url  "
                . "FROM `good` g JOIN `category` c ON c.`id` = g.`category_id` AND c.`menu_visible` = 1 WHERE g.`menu_visible` = 1 "
                . " AND (g.`name` like '%" . $text . "%' OR g.`description` like '%". $text ."%'"
                . " ORDER BY g.`sort_index`;";

            $result = DB::$mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                $good = new Good($row["id"], $row["category_id"], $row["name"], $row["image_url"], $row["description"], $row["kcal_per_100g"]);
                $good->menu_visible = $row["menu_visible"];

                $category = new Category($row["category_id"], $row["c_name"], $row["c_image_url"]);
                $good->category = $category;

                $goods[$row["id"]] = $good;
            }
        }

        return $goods;
    }

    public static function get_all_goods()
    {
        $query = "SELECT "
            . "g.`id`, g.`category_id`, g.`name`, g.`image_url`, g.`description`, g.`kcal_per_100g`, g.`menu_visible`, c.`name` AS c_name, c.`image_url` AS c_image_url "
            . "FROM `good` g "
            . "JOIN `category` c ON c.`id` = g.`category_id` WHERE g.`menu_visible` IN(0,1) ORDER BY g.`sort_index`;";

        $result = DB::$mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            $good = new Good($row["id"], $row["category_id"], $row["name"], $row["image_url"], $row["description"], $row["kcal_per_100g"]);
            $good->menu_visible = $row["menu_visible"];

            $category = new Category($row["category_id"], $row["c_name"], $row["c_image_url"]);
            $good->category = $category;

            self::$goods[$row["id"]] = $good;
        }

        return self::$goods;
    }

    public static function get_goods_by_category_id($category_id, $menu_visible = 1, $start_limit = 0, $amount = 12)
    {
        $goods = null;

        $menu_visible_part = $menu_visible == null ? " IN (0,1) " : " = " . $menu_visible;

        $query = "SELECT "
            . "g.`id`, g.`category_id`, g.`name`, g.`image_url`, g.`description`, g.`kcal_per_100g`, g.`menu_visible`, c.`name` AS c_name, c.`image_url` AS c_image_url "
            . "FROM `good` g "
            . "JOIN `category` c ON c.`id` = g.`category_id` WHERE g.`menu_visible` " . $menu_visible_part
            . " AND g.`category_id` = '" . $category_id . "' "
            . "ORDER BY g.`sort_index` "
            . "LIMIT " . $start_limit . ", " . $amount . " ;";

        $result = DB::$mysqli->query($query);

        if (DB::$mysqli->error) {
            echo $query . "<br>";
            print_r(DB::$mysqli->error);
            return null;
        }


        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $good = new Good($row["id"], $row["category_id"], $row["name"], $row["image_url"], $row["description"], $row["kcal_per_100g"]);
                $good->menu_visible = $row["menu_visible"];

                $category = new Category($row["category_id"], $row["c_name"], $row["c_image_url"]);
                $good->category = $category;

                $goods[$row["id"]] = $good;
            }
        }

        return $goods;
    }

    public static function get_goods_count_by_category_id($category_id, $menu_visible = 1)
    {
        $count = 0;

        $menu_visible_part = $menu_visible == null ? " IN (0,1) " : " = " . $menu_visible;

        $query = "SELECT "
            . "COUNT(*) AS goods_count "
            . "FROM `good` g "
            . "WHERE g.`category_id` = '" . $category_id . "' AND "
            . " g.`menu_visible` " . $menu_visible_part . " ;";

        $result = DB::$mysqli->query($query);

        if (DB::$mysqli->error) {
            echo $query . "<br>";
            print_r(DB::$mysqli->error);
            return null;
        }

        while ($row = $result->fetch_assoc()) {
            $count = $row["goods_count"];
        }

        return $count;
    }

    public static function get_popular_goods()
    {
        $goods = array();

        $query =
"SELECT
g.`id`, g.`category_id`, g.`name`, g.`image_url`, g.`description`, g.`kcal_per_100g`, g.`menu_visible`, c.`name` AS c_name, c.`image_url` AS c_image_url, pop.`sum_amount`
FROM (
	SELECT SUM(od.`amount`) as sum_amount, p.`good_id` as good_id
    FROM `portion` p
    JOIN `order_detail` od ON od.`portion_id` = p.`id`
    GROUP BY p.`good_id`
    ORDER BY SUM(od.`amount`) DESC
) AS pop
JOIN `good` g ON g.id = pop.`good_id`
JOIN `category` c ON c.`id` = g.`category_id`
WHERE g.`menu_visible` IN(1) AND c.`menu_visible` IN(1)
ORDER BY g.`sort_index`
LIMIT 0, 12";

        $result = DB::$mysqli->query($query);

        if (isset($result) && $result != null) {
            while ($row = $result->fetch_assoc()) {
                $good = new Good($row["id"], $row["category_id"], $row["name"], $row["image_url"], $row["description"], $row["kcal_per_100g"]);
                $good->menu_visible = $row["menu_visible"];

                $category = new Category($row["category_id"], $row["c_name"], $row["c_image_url"]);
                $good->category = $category;

                $goods[$row["id"]] = $good;
            }
        }

        return $goods;
    }

    public static function get_good_by_id($good_id)
    {

        $query = "SELECT g.`id`, g.`category_id`, g.`name`, g.`image_url`, g.`description`, g.`kcal_per_100g`, g.`menu_visible` "
            . "FROM `good` g "
            . "WHERE g.`id` = '" . $good_id . "' ORDER BY g.`sort_index`;";

        $result = DB::$mysqli->query($query);

        $good = null;
        while ($row = $result->fetch_assoc()) {
            $good = new Good($row["id"], $row["category_id"], $row["name"], $row["image_url"], $row["description"], $row["kcal_per_100g"]);
            $good->menu_visible = $row["menu_visible"];
        }

        return $good;
    }

    public static function save_good(Good $good)
    {
        if (isset($good)) {
            $query = "";
            if (isset($good->id) && strlen($good->id) > 0) {
                $query = $good->mysql_update();
            } else {
                $good->sort_index = self::get_last_sort_index() + 1;
                $query = $good->mysql_insert();
            }

            DB::$mysqli->query($query);

            if (DB::$mysqli->error) {
                print_r(DB::$mysqli->error);
                return null;
            }

            return $good;
        } else {
            echo "Ошибка при сохранении товара! Товар не указан!";
            return null;
        }
    }

    public static function get_last_sort_index()
    {
        $query = "SELECT MAX(`sort_index`) as `sort_index` FROM `good`";

        $result = DB::$mysqli->query($query);

        if (DB::$mysqli->error) {
            print_r(DB::$mysqli->error);
            echo $query;
            return 0;
        }

        while ($row = $result->fetch_assoc()) {
            $last_sort_index = $row["sort_index"];
        }

        return $last_sort_index;
    }
}

?>