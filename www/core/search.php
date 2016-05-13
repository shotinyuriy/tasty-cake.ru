<?php
require_once("m-category.php");
require_once("m-good.php");
require_once("m-portion.php");
require_once("v-category.php");
require_once("v-good.php");
require_once("util.php");

$query = isset($_REQUEST["query"]) ? StringUtils::convert($_REQUEST["query"], 'string') : "";

$categories = Categories::search_categories_by_text($query);

$goods = Goods::search_goods_by_text($query); ?>

<div class="row">

    <? if ($categories) { ?>
        <div id='cats'>
            <? CategoryView::category_to_div($categories); ?>
        </div>
    <? } ?>

    <? if ($goods) { ?>
        <div id='goods'>
            <? $goods = Portions::fill_portions_of_goods($goods);
            GoodView::good_to_div($goods, false); ?>
        </div>
    <? } ?>

    <? if (!$categories && !$goods) { ?>
        <p>По вашему запросу ничего не найдено!</p>
    <? } ?>

</div>