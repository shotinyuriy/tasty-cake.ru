<?php
require_once("m-news.php");
require_once("util.php");

class NewsView
{
    public static function news_list_to_divs($news_list)
    {
        if (isset($news_list)) { ?>
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <? $i = 0;
                foreach ($news_list as $news) { ?>
                    <li data-target="#news" data-slide-to="<?= $i ?>" <?= $i == 0 ? "class=\"active\"" : "" ?>></li>
                    <?
                    $i++;
                }

                $i = 0;
                ?>
            </ol>
            <div class="carousel-inner" role="listbox">
                <? foreach ($news_list as $news) { ?>
                    <div class='item <?= $i == 0 ? "active" : "" ?>'>
                        <div class="container">
                            <div class="img-holder">
                                <img class="news-img" src='<?= home_href ?>img/<?= $news->image_url ?>'
                                     alt="slide #<?= $i ?>">
                            </div>
                            <div class="carousel-caption">
                                <div class="row">
                                    <div class="col-lg-4 col-lg-offset-8">
                                        <h1><?= $news->title ?></h1>
                                        <p><?= $news->description ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?
                    $i++;
                } ?>
            </div>
            <a class="left carousel-control" href="#news" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#news" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        <? }
    }

    public static function news_list_to_cms($news_list)
    {
        ?>
        <? if (isset($news_list)) {
        $i = 0;
        ?>
        <div class="row">
            <div class="col-lg-12"><a href='../core/c-news.php?method=edit' class='edit'>Добавить новость</a></div>
        </div>
        <div class="row">
            <? foreach ($news_list as $news) { ?>

                <div class='news-item col-lg-4 col-xs-12'>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="category-icon">
                                <? if ($news->image_url) { ?>
                                    <img class="news-img" src='<?= home_href ?>img/<?= $news->image_url ?>'
                                         alt=<?= $i ?>"-slide">
                                <? } ?>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-12">
                            <div class="good-info">
                                <div class="good-name">
                                    <p><?= $news->title ?></p>
                                </div>
                                <div>
                                    <p class="cart-good-description"><?= $news->description ?></p>
                                </div>
                                <div>
                                    <a href='../core/c-news.php?method=edit&id=<?= $news->id ?>'
                                       class='edit btn btn-primary'>Изменить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?
                $i++;
            } ?>
        </div>
    <? } ?>
    <? }

    public static function edit_form($news)
    {
        if ($news == null) return;
        $title = ($news->id == null ? 'Добавить' : 'Изменить') . ' новость';
        $check_menu_invisible = $news->menu_visible == null || $news->menu_visible == 0 ?
            'checked' : null;
        $check_menu_visible = $news->menu_visible == 1 ? 'checked' : null;
        ?>
        <div id='add_category_div' class='form'>
            <form id='add_news_form' class='edit-form' method='post' enctype='multipart/form-data' action='c-news.php'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editorFormLabel"><?= $title ?></h4>
                </div>

                <div class="modal-body">
                    <input type='hidden' name='method' value='save'>
                    <input type='hidden' name='id' value='<?= $news->id ?>'>
                    <? if (isset($news->image_url)) { ?>
                        <input type='hidden' name='stored_image_url' value='<?= $news->image_url ?>'>
                    <? } ?>
                    <div class="form-group">
                        <label class='little' for="name">Название:</label>
                        <div class='little'>
                            <input type='text' name='title' value='<?= $news->title ?>' class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little' for="description">Описание:</label>
                        <div class='little'>
                            <textarea name='description' class="form-control"><?= $news->description ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little'>Изображение</label>
                        <div>
                            <img class='image_url' src='../img/<?= $news->image_url."?time=".time() ?>'
                                 alt='<?= $news->image_url ? $news->image_url : 'Нет изображения' ?>'>
                            <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
                            <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                            <input type='file' name='image_url' value='Выбрать' class='btn btn-primary'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little'>Отображать на сайте?</label>
                        <div class='little'>
                            <div class="radio">
                                <label>
                                    <input type='radio' name='menu_visible' value='1' <?= $check_menu_visible ?> >
                                    Да
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type='radio' name='menu_visible' value='0' <?= $check_menu_invisible ?> >
                                    Нет
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
        <?
    }
}

?>