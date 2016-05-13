<?php
session_start();
require_once("m-good.php");
require_once("m-portion.php");
require_once("v-good.php");
require_once("util.php");

$goods_on_page = 12.0;

$cms = false;
if (isset($_REQUEST['cms']) && $_REQUEST['cms'] == true) {
    $cms = true;
}

$category_id = null;

if (isset($_REQUEST["method"])) {
    $method = StringUtils::convert($_REQUEST["method"], 'string');

    if ($method == 'save') {
        save();
    } elseif ($method == 'edit') {
        edit();
    } elseif ($method == 'popular') {
        popular($goods_on_page, $cms);
    }
} else {
    show_goods($goods_on_page, $cms);
}

function save()
{
    include("cms-result-header.php");

    $id = isset($_POST["id"]) ? StringUtils::convert($_POST["id"], 'string') : null;
    $category_id = isset($_POST["category_id"]) ? StringUtils::convert($_POST["category_id"], 'string') : null;
    $name = isset($_POST["name"]) ? StringUtils::convert($_POST["name"], 'string') : null;
    $stored_image_url = isset($_POST["stored_image_url"]) ? StringUtils::convert($_POST["stored_image_url"], 'string') : null;
    $description = isset($_POST["description"]) ? StringUtils::convert($_POST["description"], 'string') : null;
    $menu_visible = isset($_POST["menu_visible"]) ? StringUtils::convert($_POST["menu_visible"], 'int') : null;
    $kcal_per_100g = isset($_POST["kcal_per_100g"]) ? StringUtils::convert($_POST["kcal_per_100g"], 'int') : null;

    $portion_id = isset($_POST["portion_id"]) ? StringUtils::convert($_POST["portion_id"], 'int') : null;
    $portion_id = $portion_id <= 0 ? null : $portion_id;

    $amount = isset($_POST["amount"]) ? StringUtils::convert($_POST["amount"], 'int') : 1;
    $gramms = isset($_POST["gramms"]) ? StringUtils::convert($_POST["gramms"], 'int') : 0;
    $milliliters = isset($_POST["milliliters"]) ? StringUtils::convert($_POST["milliliters"], 'int') : 0;
    $price = isset($_POST["price"]) ? StringUtils::convert($_POST["price"], 'double') : 0.0;
    $image_url = null;

    $good = new Good($id, $category_id, $name, $image_url, $description, $kcal_per_100g);
    $good->menu_visible = $menu_visible;

    $id = $good->generate_id();

    $portion = new Portion($portion_id, $id, $amount, $gramms, $milliliters, $price);

    if ($good != null && $id != null) {
        $good->image_url = FileUtils::saveFile($id);
    }

    if ($good->image_url == null) {
        $good->image_url = $stored_image_url;
    }

    $good = Goods::save_good($good);

    if ($portion->amount > 0 || $portion->gramms > 0 || $portion->milliliters > 0) {
        $portion = Portions::save_portion($portion);
    }

    include("cms-result-footer.php");
}

function edit()
{
    $id = isset($_REQUEST["id"]) ? StringUtils::convert($_REQUEST["id"], 'string') : null;

    $good = Goods::get_good_by_id($id);
    $goods[$id] = $good;

    $goods = Portions::fill_portions_of_goods($goods);

    $good = $goods[$id];

    if ($good == null) {
        $category_id = get_category_id();

        $good = new Good(null, $category_id, null, null, null, null);
    }

    GoodView::edit_form($good);
}

function get_category_id()
{
    $category_id = null;

    if (isset($_REQUEST["category_id"])) {

        $category_id = StringUtils::convert($_REQUEST["category_id"], 'string');
    } elseif (isset($_SESSION["category_id"])) {
        $category_id = $_SESSION["category_id"];
    }

    return $category_id;
}

function popular($goods_on_page, $cms) {
    $goods = Goods::get_popular_goods();

    if (isset($goods) && $goods != null) {
        $goods = Portions::fill_portions_of_goods($goods);
        //$goods = Categories::fill_categories( $goods );
    }

    GoodView::good_to_div($goods, $cms);
}

function show_goods($goods_on_page, $cms)
{
    $page_id = StringUtils::convert($_REQUEST["pageId"], 'int');
    $page_id = $page_id > 0 ? $page_id : 1;

    $category_id = get_category_id();
    if ($category_id) $_SESSION["category_id"] = $category_id;

    if ($category_id != null) {
        $menu_visible = $cms ? null : 1;
        $count = Goods::get_goods_count_by_category_id($category_id, $menu_visible);

        if ($count > 0 && $page_id > 0) {
            $page_count = ( int )($count / $goods_on_page) + 1;
            $start_limit = ($page_id - 1) * $goods_on_page;

            $goods = Goods::get_goods_by_category_id($category_id, $menu_visible, $start_limit, $goods_on_page);
            if ($page_count && $page_count > 1) {
                NavigationUtils::print_pagination($page_count, $page_id, "../core/c-good.php");
            }
        }
    } else {
        GoodView::print_error_message();
    }

    if (isset($goods) && $goods != null) {
        $goods = Portions::fill_portions_of_goods($goods);
        //$goods = Categories::fill_categories( $goods );
    }

    GoodView::good_to_div($goods, $cms, $category_id);
}

?>