<?php
require_once("m-order.php");
require_once("util.php");

class OrderView
{

    public static function orders_to_table($orders)
    {
        ?>

        <table class='orders table'>
            <tr class='orders'>
                <th>Номер заказа</th>
                <th>Дата и время заказа</th>
                <th>Сумма</th>
                <th>Номер телефона</th>
                <th>Клиент</th>
                <th>Адрес доставки</th>
                <th>Статус</th>
                <th>Новый статус</th>
            </tr>

            <? if (isset($orders) && count($orders) > 0) {
                foreach ($orders as $order) { ?>
                    <tr class='orders'>
                        <td><a href='../core/c-order.php?method=details&id=<?= $order->id ?>'
                               class='edit'><?= $order->id ?></a></td>
                        <td><?= $order->date_time_format("d.m.Y H:i:s") ?></td>
                        <td><?= round($order->total_cost(), 2) . " руб" ?></td>
                        <td><?= $order->phone_number ?></td>
                        <td><?= $order->customer_name ?></td>
                        <td><?= $order->address ?></td>
                        <td><?= $order->status_name() ?></td>
                        <td><?= $order->print_available_actions() ?></td>
                    </tr>
                <? }
            } ?>
        </table>
        <?
    }

    public static function order_to_details(Order $order, $cms)
    {
        ?>
        <div class="table-caption">
            <div class="row">

                <div class="col-lg-5">Товар</div>
                <div class="col-lg-2">Количество</div>
                <div class="col-lg-2">Цена</div>
                <div class="col-lg-2">Стоимость</div>
                <div class="col-lg-1"></div>

            </div>
        </div>

        <? if ($order != null) {
        Order::check_discounts_and_gifts($order, $order->total_cost());
        if (isset($order->details))
            foreach ($order->details as $detail) {
                if (isset($detail->portion) && isset($detail->portion->good)) {
                    $id = $detail->portion->id;
                    ?>
                    <div class='row table-caption' id='row_<?= $id ?>'>
                        <div class='col-lg-2'>
                            <? if ($detail->portion->good->image_url) { ?>
                                <div class="cart-good-icon">
                                    <img src='../menu-img/<?= $detail->portion->good->image_url ?>'
                                         alt='<?= $detail->portion->good->id ?>'</img>
                                </div>
                            <? } ?>
                        </div>
                        <div class='col-lg-3'>

                            <p class='cart-good'><?= $detail->portion->good->name ?></p>
                            <p class='cart-good-description'><?= $detail->portion->good->description ?></p>

                            <p class='cart-good-description'>

                                <?= $detail->portion->amount > 0 ? $detail->portion->amount . " шт." : "" ?>
                                <?= $detail->portion->gramms > 0 ? " " . $detail->portion->gramms . " гр." : "" ?>
                                <?= $detail->portion->milliliters > 0 ? " " . $detail->portion->milliliters . " мл." : "" ?>
                            </p>
                        </div>

                        <div class='col-lg-2'>

                            <? if ($order->status == null || $order->status == 0) { ?>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                      <input type='button' value='-' id='dec_<?= $id ?>'
                                             class='decrease btn btn-squared'>
                                    </span>
                                    <input type='text' value='<?= $detail->amount ?>' id='amt_<?= $id ?>'
                                           class='form-control amount'/>
                                    <span class="input-group-btn">
                                      <input type='button' value='+' id='inc_<?= $id ?>'
                                             class='increase btn btn-squared'>
                                    </span>
                                </div><!-- /input-group -->
                            <? } else { ?>
                                <div>
                                    <span id='amt_<?= $id ?>' class='amount'><?= $detail->amount ?></span>
                                </div>
                            <? } ?>
                        </div>
                        <div class="col-lg-2">
                            <span class='cart-price'><?= round($detail->portion->price, 2) . " руб." ?></span>
                        </div>
                        <div class='col-lg-2'
                             id='cost_<?= $id ?>'><?= round($detail->calculate_cost(), 2) . " руб." ?></div>
                        <div class="col-lg-1">
                            <button id='del_<?= $id ?>' class='delete btn btn-squared'>
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?
                }
            }

        if (isset($order->gifts) && count($order->gifts) > 0) {
            $detail = $order->gifts["sum"];
            if ($detail != null) { ?>
                <div class="row">
                    <div class="col-lg-12"><p class="part-header">Подарки</p></div>
                </div>
                <div class='row table-caption' id='row_<?= $id ?>'>
                    <div class='col-lg-2'>
                        <? if ($detail->portion->good->image_url) { ?>
                            <div class="cart-good-icon">
                                <img src='../menu-img/<?= $detail->portion->good->image_url ?>'
                                     alt='<?= $detail->portion->good->id ?>'</img>
                            </div>
                        <? } ?>
                    </div>

                    <div class='col-lg-3'>
                        <p class='cart-good'><?= $detail->portion->good->name ?></p>
                        <p class='cart-good-description'><?= $detail->portion->good->description ?></p>
                        <p class='cart-good-description'>
                            <?= $detail->portion->amount > 0 ? $detail->portion->amount . " шт." : "" ?>
                            <?= $detail->portion->gramms > 0 ? " " . $detail->portion->gramms . " гр." : "" ?>
                            <?= $detail->portion->milliliters > 0 ? " " . $detail->portion->milliliters . " мл." : "" ?>
                        </p>
                    </div>
                    <div class='col-lg-2'>
                        <div>
                            <span id='amt_<?= $id ?>' class='amount'><?= $detail->amount ?></span>
                        </div>
                    </div>
                    <div class='col-lg-3 col-lg-offset-2'
                         id='cost_<?= $id ?>'><?= round($detail->calculate_cost(), 2) . " руб." ?></div>
                </div>
                <?
            }
        }
    } ?>

        <div class='row'>
            <div class='col-lg-4 col-lg-offset-6'> Итого:</div>
            <div class='col-lg-2' id='total_cost'>
                <?= StringUtils::convert($order ? $order->total_cost() : 0.0, 'money') ?>
            </div>
        </div>

        <? if ($order && isset($order->discounts)) {
        foreach ($order->discounts as $discount) {
            if ($discount["value"] > 0) {
                ?>
                <div class='row'>
                    <div
                        class='col-lg-4 col-lg-offset-6'><?= $discount["descr"] . " " . $discount["value"] . "%: " ?></div>
                    <div class='col-lg-2'>
                        <?= StringUtils::convert($order->discount_sum(), 'money') ?>
                    </div>
                </div>
            <? }
        } ?>

        <div class='row'>
            <div class='col-lg-4 col-lg-offset-6'> Итого со скидкой:</div>
            <div class='col-lg-2'>
                <?= StringUtils::convert($order->total_cost() - $order->discount_sum(), 'money') ?>
            </
            >
        </div>
    <? } ?>

        <?
    }

    public static function filter_form()
    {
        $date_from = '2013-12-31';
        $date_to = date("Y-m-d H:i:s");
        ?>
        <center>
            <h3>Заказы</h3>
            <form id='search_orders_form' action='../core/c-order.php'>
                <input type='hidden' name='method' value='searchOrders'>
                <table>
                    <tr>
                        <th>с:</th>
                        <td>
                            <input type='date' name='dateFrom' value="<? php ?>">
                        </td>
                        <th>по:</th>
                        <td>
                            <input type='date' name='dateTo'>
                        </td>
                        <th>статус:</th>
                        <td>
                            <select name='statuses'>
                                <option value=''>Любой</option>
                                <? for ($i = 0; $i < count(Order::$status_names); $i++) { ?>
                                    <option value='<?= $i ?>'>
                                        <?= Order::$status_names[$i] ?>
                                    </option>
                                <? } ?>
                            </select>
                        </td>
                        <th>Номер телефона</th>
                        <td><input type='text' name='phoneNumber'></td>
                    <tr>
                        <td colspan='6'>
                            <input type='submit' value='Показать'/>
                        </td>
                    </tr>
                    </tr>
                </table>
            </form>
            <div id='orders_div'>

            </div>
        </center>
        <?
    }
}

?>