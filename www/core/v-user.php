<?php
require_once("m-user.php");
require_once("util.php");

class UserView
{
    public static function edit_form($user)
    {
        if ($user == null) return;
        $title = ($user->id == null ? 'Добавить' : 'Изменить') . ' пользователя';
        ?>
        <div id='change_password' class='form'>
            <form id='add_news_form' method='post' action='c-user.php'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="editorFormLabel"><?= $title ?></h4>
                </div>

                <div class="modal-body">
                    <input type='hidden' name='method' value='save'>
                    <? if (isset($user->id) && $user->id != null) { ?>
                        <input type='hidden' name='id' value='<?= $user->id ?>'>
                    <? } ?>
                    <div class="form-group">
                        <label class='little' for="name">Логин:</label>
                        <div class='little'>
                            <? if (isset($user->login) && $user->login != null) { ?>
                                <input type='hidden' name='login' value='<?= $user->login ?>'>
                                <p class="form-control-static"><?= $user->login ?></p>
                            <? } else { ?>
                                <input type='text' name='login' class='form-control' value=''>
                            <?
                            } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little' for="name">Роль:</label>
                        <div class='little'>
                            <? if (isset($user->role) && $user->role != null) { ?>
                                <input type='hidden' name='login' value='<?= $user->role ?>'>
                                <p class="form-control-static"><?= $user->role ?></p>
                            <? } else { ?>
                                <div class="radio">
                                    <label>
                                        <input type='radio' name='role' value='<?= User::OPERATOR ?>' checked>
                                        <?= User::OPERATOR ?>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type='radio' name='role' value='<?= User::ADMINISTRATOR ?>'>
                                        <?= User::ADMINISTRATOR ?>
                                    </label>
                                </div>
                            <?
                            } ?>
                        </div>
                    </div>
                    <? if (isset($user->id) && $user->id != null) { ?>
                        <div class="form-group">
                            <label class='little' for="name">Старый пароль:</label>
                            <div class='little'>
                                <input type='password' name='old_password' value='' class="form-control">
                            </div>
                        </div>
                    <? } ?>
                    <div class="form-group">
                        <label class='little' for="name">Новый пароль:</label>
                        <div class='little'>
                            <input type='password' name='new_password' value='' class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='little' for="name">Подтвердите пароль:</label>
                        <div class='little'>
                            <input type='password' name='confirm_password' value='' class="form-control">
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