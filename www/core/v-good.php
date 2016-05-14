<?php
require_once("m-good.php");
require_once("m-user.php");

class GoodView
{

    public static function print_error_message()
    {
        echo "Выберите категорию!";
    }

    public static function good_to_div($goods, $cms, $category_id = "")
    { ?>
        <? if ($cms) { ?>
            <? if ($_SESSION["role"] == User::ADMINISTRATOR) { ?>
            <div class="row">
                <div class="col-lg-12">
                <a href='../core/c-good.php?method=edit' class='edit btn btn-success'>Добавить товар</a>
                <a href='../core/c-category.php?method=edit&category_id=<?= $category_id ?>' class='edit btn btn-success'>
                    Добавить подкатегорию</a>
                <a href='../core/c-user.php?method=edit' class='edit btn btn-success'>Добавить пользователя</a>
                </div>
            </div>
        <? } ?>
    <? } ?>
        <? if ($goods) { ?>
        <div class="row">
            <? foreach ($goods as $good) {
                $image_url = ($good->image_url ? $good->image_url : ($good->category ? $good->category->image_url : ""));
                foreach ($good->portions as $portion) {
                    //$portion = $good->get_first_portion();
                    $is_not_mv = $cms && $good->menu_visible == 0 ? "style='border-color: grey;'" : ""; ?>

                    <div class="col-lg-4" <?= $is_not_mv ?>>
                        <div class="good-item">
                            <div class="category-icon">
                                <? if ($image_url) { ?>
                                    <div class='menuimg'><img src='../menu-img/<?= $image_url."?time=".time() ?>'/></div>
                                <? } ?>
                            </div>
                            <div class="good-info">
                                <div class="good-name">
                                    <p><?= $good->name ?></p>
                                </div>
                                <div>
                                    <? if (strlen($good->description) > 128) { ?>
                                        <p class="cart-good-description" data-toggle="tooltip" data-placement="right"
                                           title="<?= $good->description ?>">Состав: ...</p>
                                    <? } else { ?>
                                        <p class="cart-good-description"><?= $good->description ?></p>
                                    <? } ?>
                                </div>
                                <div>
                                    <? if (isset($portion)) { ?>
                                        <p class="cart-good-description"><?= $portion->amount . "шт." ?>
                                            <? if ($portion->gramms > 0) { ?>
                                                &nbsp;<?= $portion->gramms ?>г.
                                            <? } else if ($portion->milliliters > 0) { ?>
                                                &nbsp;
                                                <?= $portion->milliliters >= 100 ? round($portion->milliliters / 1000.0, 2) . "л." : $portion->milliliters . "мл." ?>

                                            <? } ?>
                                        </p>
                                    <? }
                                    if ($good->kcal_per_100g && $good->kcal_per_100g > 0) { ?>
                                        <p class="cart-good-description">
                                            <?= $good->kcal_per_100g ?> ккал на 100г
                                        </p>
                                    <? } ?>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="amount"><?= round($portion->price, 2) . "р." ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <form id='<?= $good->id ?>"_tocart' class='tocart' action='../core/c-cart.php'>

                                            <? if ($cms == true) { ?>
                                                <a href='../core/c-good.php?method=edit&id=<?= $good->id ?>'
                                                   class='edit btn btn-primary'>Изменить</a>
                                            <? } else {
                                                if (isset($portion)) { ?>
                                                    <input type='hidden' name='portionId' value='<?= $portion->id ?>'>
                                                    <input type='hidden' name='method' value='add'>
                                                    <button type='submit' class="btn btn-buy">Купить</button>
                                                <? }
                                            } ?>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? }
            } ?>
        </div>
    <? } ?>
    <? }

    public static function edit_form($good)
    {
        if ($good == null) return;
        $title = ($good->id == null ? 'Добавить' : 'Изменить') . ' блюдо / напиток';
        $check_menu_invisible = $good->menu_visible == null || $good->menu_visible == 0 ?
            'checked' : null;
        $check_menu_visible = $good->menu_visible == 1 ? 'checked' : null;
        ?>

        <div id='add_good_div'>

            <form id='add_good_form' class='edit-form' method='post' enctype='multipart/form-data' action='c-good.php' accept-charset="utf-8">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editorFormLabel"><?= $title ?></h4>
                </div>

                <div class="modal-body">

                    <input type='hidden' name='method' value='save'>
                    <input type='hidden' name='id' value='<?= $good->id ?>'>
                    <input type='hidden' name='category_id' value='<?= $good->category_id ?>'>
                    <input type='hidden' name='stored_image_url' value='<?= $good->image_url ?>'>
                    <div class="form-group">
                        <label class='little'>Название:</label>
                        <div class='little'>
                            <input type='text' name='name' class="form-control" value='<?= $good->name ?>'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little'>Описание:</label>
                        <div class='little'>
                            <textarea name='description' rows='5' cols='30'
                                      class="form-control"><?= $good->description ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little'>Изображение</label>
                        <div>
                            <img class='image_url' src='../menu-img/<?= $good->image_url."?time=".time() ?>'
                                 alt='<?= $good->image_url ? $good->category->image_url : 'Нет изображения' ?>'
                                 width='197'>
                            <input type='file' name='image_url' value='Выбрать'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little'>Отображать в меню?</label>
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
                    <div class="form-group">
                        <label class='little'>Ккал на 100 грамм?</label>
                        <div class='little'>
                            <input type='text' name='kcal_per_100g' value='<?= $good->kcal_per_100g ?>'
                                   class="form-control">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <caption>Порции</caption>
                            <tr>
                                <th class="col-lg-2">
                                    Действия
                                </th>
                                <th class="col-lg-2">
                                    Штук
                                </th>
                                <th class="col-lg-2">
                                    Граммов
                                </th>
                                <th class="col-lg-2">
                                    Миллилитров
                                </th>
                                <th class="col-lg-2">
                                    Цена за порцию (руб.)
                                </th>
                            </tr>

                            <?php
                            if (isset($good->portions)) {
                                foreach ($good->portions as $portion) {
                                    ?>

                                    <tr>
                                        <td><a href='#' id='<?= $portion->id ?>' class='portion-edit'>Изменить</a></td>
                                        <td><?= $portion->amount ?></td>
                                        <td><?= $portion->gramms ?></td>
                                        <td><?= $portion->milliliters ?></td>
                                        <td><?= round($portion->price, 2) ?></td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>

                            <tr>
                                <td colspan='5' align='center'>Новая порция</td>
                            </tr>
                            <tr id='new-portion'>
                                <td>
                                    <input type='hidden' name='portion_id' value=''>
                                </td>
                                <td>
                                    <input type='text' name='amount' class="form-control">
                                </td>
                                <td>
                                    <input type='text' name='gramms' class="form-control">
                                </td>
                                <td>
                                    <input type='text' name='milliliters' class="form-control">
                                </td>
                                <td>
                                    <input type='text' name='price' class="form-control">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
        <?php
    }
}

?>