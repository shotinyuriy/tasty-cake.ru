<?php
class ErrorView
{

    public static function print_error_message($message)
    { ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4-4">
                    <div class="well">
                        <?= $message ?>
                    </div>
                </div>
            </div>
        </div>
    <? }
}
?>