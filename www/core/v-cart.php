<?php
	require_once( "m-cart.php" );
	require_once( "m-order.php" );
	require_once( "v-order.php" );
	
	class CartView {
		
	
		public static function cart_total_to_text( $cart ) {
			
			if( isset( $cart ) ) {
				echo ($cart->order ? $cart->order->total_cost()  : "0" )."р.";
			}
		}
		
		public static function cart_order_details_to_divs( $cart ) {
			
			if( isset( $cart ) && $cart->order && $cart->order->details ) {
				if( isset( $cart->order->id ) && $cart->order->id > 0 ) { ?>
                    <div class="row">
						<div class="col-lg-12">
							<h3 class='ordsvd info'>
								<span class="label label-info">Ваш заказ сохранен под номером <?= $cart->order->id ?></span>
							</h3>
						</div>
                    </div>
					<? self::cart_order_info( $cart->order ) ?>
				<? } ?>
				
				<? OrderView::order_to_details( $cart->order, false, false ) ?>
				
				<? if( isset( $cart->order->id ) ) { ?>
					<div id='cart_nav'> <a href='#' id='cart_new'> Новый заказ </a> </div>
				<? } else { ?>
				<div class="row">
				    <div class="col-lg-4 col-lg-offset-4">
                        <form id='is_birthday' method='POST' action='../core/c-cart.php' class="cart-form">
                            У вас сегодня день рождения?<br/>
                            <input type='radio' name='isBirthday' value='0' <?= (!$cart->order->is_birthday ? "checked" : "") ?>>Нет
                            <input type='radio' name='isBirthday' value='1' <?= ($cart->order->is_birthday ? "checked" : "") ?>>Да
                        </form>
                        <div id='cart_nav'>
                            <button class="btn btn-danger" id='cart_cancel'> Отменить заказ </button>
                            <button class="btn btn-success" id='cart_next'> Дальше </button>
                        </div>
					</div>
				</div>
						

				<? }
			} else { ?>
				<div>
					<h3>Ваша корзина пуста</h3>
					<p>Начните с <a href='../menu/' class='simple'>меню</a>
				<div>
			<? }
		}

		public static function detail_and_total_cost_to_text( $detail, $cart, $cost_before = 0 ) {
			if( isset( $detail ) && isset( $cart ) ) {
				
				$cost_after = $cart->order->total_cost();
				
				$is_need_renew = Order::check_need_renew($cost_before, $cost_after);
				
				if( $is_need_renew ) {
					echo "renew";
				} else {
					echo round( $detail->calculate_cost(), 2 )." руб.|".$cost_after." руб.";
				}
			}
		}

		public static function cart_order_page() {
			?>
				<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
				<form method='post' action='./core/c-cart.php' id='cart_order_form' class="cart-form">
                    <div class="form-group">
                        <label for="phoneNumber">Номер телефона<sup>*</sup>:</label>
                        <div class="input-group">
                            <div class="input-group-addon">+7</div>
                            <input type='text' name='phoneNumber' id='phoneNumber' maxlength='10' required  class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customerName">Как к вам обращаться:</label>
                        <input type='text' name='customerName' id='customerName' class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="customerName">Доставка:</label>
                        <div class="radio">
                            <label>
                                <input type="radio" name="selfTake" id="selfTake1" value="0" checked>
                                Нужна
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="selfTake" id="selfTake2" value="1">
                                Заберу сам
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        Адрес<sup>*</sup>:
                        <textarea id="address" name='address' cols="30" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="panel-info">
                    * - поле обязательно для заполнения
                    </div>

                    <br>
                    <div id='cart_nav'>
                        <button class="btn btn-warning" id='cart_back'> Назад </button>
                        <button class="btn btn-danger" id='cart_cancel'> Отменить заказ </button>
                        <button class="btn btn-success" id='cart_save'> Подтвердить </button>
                    </div>
				</form>
				</div>
				</div>
			<?php
		}

		public static function cart_order_info( $order ) {
			if ( $order != null ) {
				$self_take = $order->self_take == 0 ? "Нужна" : "Заберу сам";

			?>
                <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
				<form method='post' action='./core/c-cart.php' id='cart_order_form' class="form-horizontal">
						<div class="form-group">
							<label class='col-sm-2 control-label'>
								Номер телефона:
							</label>
							<div class='col-sm-10'>
								<p class="form-control-static">+7<?=$order->phone_number?></p>
							</div>
						</div>
						<div class="form-group">
							<label class='col-sm-2 control-label'>
								Как к вам обращаться:
							</label>
							<div class='col-sm-10'>
                                <p class="form-control-static"><?=$order->customer_name?></p>
							</div>
						</div>
						<div class="form-group">
							<label class='col-sm-2 control-label'>
								Доставка:
							</label>
							<div class='col-sm-10'>
                                <p class="form-control-static"><?=$self_take?></p>
							</div>
						</div>
						<? if( $order->self_take == 0 ) { ?>
						<div class="form-group">
							<label class='col-sm-2 control-label'  id='addressLabel'>
								Адрес:
							</label>
							<div class='col-sm-10'>
                                <p class="form-control-static"><?=$order->address?></p>
							</div>
						</div>
						<? } ?>
				</form>
				</div>
                </div>
			<?
			}
		}
	}
?>