<?php
	require_once("m-category.php");
	require_once("m-user.php");
	require_once("util.php");
	
	class CategoryView
	{

		public static function category_to_div($categories)
		{
			if (isset($categories)) {
				?>
				<div class='row'>
				<? foreach ($categories as $category) { ?>
					<div class="col-lg-3 padding-lr-20">
						<div class="category-item menu-category" id="<?= $category -> id ?>">
							<div class="category-icon">
								<img src='<?= home_href ?>menu-img/<?= $category -> image_url ?>'
									 alt='<?= $category -> name ?>'>
							</div>
							<div class="good-info">
								<div class="category-name">
									<p><?= $category -> name ?></p>
								</div>
							</div>
						</div>
					</div>
				<? } ?>
				</div>
			<? } else { ?>
				<center>Не найдено ни одной категории!</center>
			<?
			}
			}

			public static function cms_form($categories, $current_category_id, $cms, $class = 'main') {
			if ($_SESSION["role"] == User::ADMINISTRATOR) {
 ?>
			<div class='row'>
	        	<div class='col-lg-3'>
		            <div class='leftmenu'>
						<ul>
						    <li class='menu-category'>Категории:</li>
						    <li id='categories'>
						        <? CategoryView::category_to_li($categories, $current_category_id, true); ?>
						    </li>
						</ul>
					</div>
		        </div>
		        <div class='col-lg-9'>
		            <div id='menu'>
		            </div>
		        </div>
	    	</div>
			<? }
				}

				public static function category_to_li($categories, $current_category_id, $cms, $class = 'main')
				{
 ?>
			<ul class='<?= $class ?>'>

				<? foreach ($categories as $category) {
					$category_is_active = $category->id == $current_category_id;
					$user_is_admin = $cms == true && $_SESSION["role"] == User::ADMINISTRATOR;
					if ($category_is_active) {
						$classes = "menu-category-selected b7radius";
					} else {
						$classes = "menu-category b7radius";
					}
					$style = $cms && $category->menu_visible == 0 ? "background: #DDD;" : ""; ?>

					<li class='menu-category'>
						<a class="<?= $classes ?>" style='<?= $style ?>'
						   href='../core/c-good.php?category_id=<?= $category -> id ?>'>
							<h6><?= $category -> name ?></h6>
						</a>
						<? if ($user_is_admin) { ?>
							<a href='../core/c-category.php?method=edit&id=<?= $category -> id ?>' class='edit btn btn-success'>Изменить</a>
							<a href='../core/c-category.php?method=delete&id=<?= $category -> id ?>' class='edit btn btn-danger'>Изменить</a>
						<? } else { ?>
							<div></div>
						<? } ?>

						<?
						if (isset($category -> sub_categories) && count($category -> sub_categories) > 0) {
							self::category_to_li($category -> sub_categories, $current_category_id, $cms, 'sub-category');
						}
						?>

					</li>
					<?
					}
 ?>

			</ul>

			<? if ($cms && $class == 'main' && $_SESSION["role"] == User::ADMINISTRATOR) { ?>
			<a href='../core/c-category.php?method=edit' class='edit  btn btn-success'>Добавить категорию</a>
		<? }
				}

				public static function edit_form($category)
				{
				if ($category == null) return;
				$title = ($category->id == null ? 'Добавить' : 'Изменить') . ' категорию';
				$check_menu_invisible = $category->menu_visible == null || $category->menu_visible == 0 ?
				'checked' : null;
				$check_menu_visible = $category->menu_visible == 1 ? 'checked' : null;
			?>
			<div id='add_category_div' class='form'>
					<form id='add_category_form' class='edit-form' method='post' enctype='multipart/form-data' action='c-category.php'  accept-charset='utf-8'>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="editorFormLabel"><?= $title ?></h4>
						</div>

						<div class="modal-body">
							<input type='hidden' name='method' value='save'>
							<input type='hidden' name='id' value='<?= $category -> id ?>'>
							<input type='hidden' name='parent_category_id' value='<?= $category -> parent_category_id ?>'>
							<input type='hidden' name='stored_image_url' value='<?= $category -> image_url ?>'>
							<div class="form-group">
								<label class='little'>Название:</label>
								<div class='little'>
									<input type='text' name='name' value='<?= $category -> name ?>' class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class='little'>Изображение</label>
								<div>
									<img class='image_url' src='../menu-img/<?= $category -> image_url . "?time=" . time() ?>'
										 alt='<?= $category -> image_url ? $category -> image_url : 'Нет изображения' ?>'
										 width='197'>
									<input type='file' name='image_url' value='Выбрать'>
								</div>
							</div>
							<div class="form-group">
								<label>Отображать в меню?</label>
								<div>
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
			<?php
			}
			}
		?>
		
