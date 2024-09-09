<?php

use common\components\Debugger as d;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>
<style>

</style>
<div class="row">
    <div class="col-md-12">
        <div class="tab<?=$tab_index?>-buttons" style="position: relative;">

            <div class="form-gorup">
                <div class="mini-form">
                    <button name="test_logs" class="btn btn-sm btn-primary">Нажать</button>
                    &nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <br>
        </div>
        <?=d::resDebug(false, 'res-tab' . $tab_index);?>
    </div>
</div>
<br><br>
<?php
$action = 'logs';
$this->registerJs(<<<JS
//JS
$(function(){});
var params = {};
params['action'] = '{$action}';
tabsAjax.call(this, '{$tab_index}', params);
JS
)
?>
