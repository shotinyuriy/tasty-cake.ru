<?php
require_once("cms-auth.php");
require_once("v-category.php");
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='utf-8'">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="http://tasty-cake.ru/favicon.ico">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="..">
                <img src="../img/logo-01.png" alt="Tasty Cake" class="fill"/>
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class='edit' id="userinfo" href="../core/c-user.php?method=edit&login=<?= $_SESSION["login"] ?>">
                        <table>
                            <tr>
                                <td>
                                    <i class="glyphicon glyphicon-user nav-icon"></i>
                                </td>
                                <td nowrap>
                                    <div class="nav-info">
                                        <p class="nav-text"><?= $_SESSION["login"] ?></p>
                                        <p class="nav-details"><?= $_SESSION["role"] ?></p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </a>
                </li>
                <li>
                    <a class='exit' href='../core/c-exit.php'>
                        <table>
                            <tr>
                                <td>
                                    <i class="glyphicon glyphicon-log-out nav-icon"></i>
                                </td>
                                <td nowrap>
                                    <div class="nav-info">
                                        <p class="nav-text">Выход</p>
                                        <p class="nav-details">&nbsp;</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
</nav>
<?php
if ($_SESSION["role"] == User::ADMINISTRATOR) {
    $categories = Categories::get_all_categories();
    $current_category_id = "";

    if (isset($_SESSION["category_id"])) {
        $current_category_id = StringUtils::convert($_SESSION["category_id"], 'string');
    }
}
?>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-lg-3'>
            <div class='leftmenu'>
                <? if ($_SESSION["role"] == User::ADMINISTRATOR) { ?>
                <ul>
                    <li class='menu-category'>Категории:</li>
                    <li id='categories'>
                        <? CategoryView::category_to_li($categories, $current_category_id, true); ?>
                    </li>
                </ul>
                <? } ?>
                <a class='menu-category' href='../core/c-order.php?method=filterForm'>Заказы</a>
                <? if ($_SESSION["role"] == User::ADMINISTRATOR) { ?>
                <a class='menu-category' href='../core/c-news.php?method=newsList'>Новости</a>
                <? } ?>

            </div>
        </div>
        <div class='col-lg-9'>
            <div id='menu'>
            </div>
        </div>
    </div>
</div>

<?
include("category-form.php");
?>
<script src="../script/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="./api.js"></script>
</body>
</htmL>