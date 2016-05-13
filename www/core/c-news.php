<?php
session_start();
require_once("m-news.php");
require_once("v-news.php");
require_once("util.php");

if (isset($_REQUEST["method"])) {
    $method = StringUtils::convert($_REQUEST["method"], 'string');

    if ($method == 'save') {
        save();
    } elseif ($method == 'newsList') {
        news_list();
    } elseif ($method == 'edit') {
        edit();
    }
} else {
    $news_list = NewsList::get_all_news(1);
    NewsView::news_list_to_divs($news_list);
}

function save() {
    include("cms-result-header.php");

    $id = isset( $_POST[ "id" ] ) ? StringUtils::convert( $_POST[ "id" ], 'string' ) : null;
    $title = isset( $_POST[ "title" ] ) ? StringUtils::convert( $_POST[ "title" ], 'string' ) : null;
    $stored_image_url = isset( $_POST[ "stored_image_url" ] ) ? StringUtils::convert( $_POST[ "stored_image_url" ], 'string' ) : null;
    $description = isset( $_POST[ "description" ] ) ? StringUtils::convert( $_POST[ "description" ], 'string' ) : null;
    $menu_visible = isset( $_POST[ "menu_visible" ] ) ? StringUtils::convert( $_POST[ "menu_visible" ], 'int' ) : null;
    $image_url = null;

    $news = new News();
    $news->id = $id;
    $news->title = $title;
    $news->description = $description;
    $news->image_url = $image_url;
    $news->menu_visible = $menu_visible;

    if( $news != null && $id != null ) {
        $news->image_url = FileUtils::saveFile( $id, 'img' );
    }

    if( $news->image_url == null ) {
        $news->image_url = $stored_image_url;
    }

    $news = NewsList::save_news( $news );

    include("cms-result-footer.php");
}

function news_list() {
    $news_list = NewsList::get_all_news();
    NewsView::news_list_to_cms($news_list);
}

function edit() {

    $id = isset( $_REQUEST[ "id" ] ) ?  StringUtils::convert( $_REQUEST[ "id" ], 'string' ) : null;

    $news = null;
    if( $id != null ) {
        $news = NewsList::get_news_by_id( $id );
    }

    if( $news == null ) {
        $news = new News();
    }

    NewsView::edit_form( $news );
}

?>