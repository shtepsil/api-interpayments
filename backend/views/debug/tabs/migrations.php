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
                    <div class="h4 tab-header">Запуск всех миграций вручную</div>
                    <button name="migrations_run" class="btn btn-sm btn-primary">Запустить миграции</button>
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
$action = 'migrations';
$this->registerJs(<<<JS
//JS
$(function(){});
var params = {};
params['action'] = '{$action}';
tabsAjax.call(this, '{$tab_index}', params);
JS
)
?>
