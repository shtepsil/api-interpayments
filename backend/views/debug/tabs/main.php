<?php

use common\components\Debugger as d;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>
<style>
 .cache-actions > div {
     float: left;
 }
 .cache-actions > .clear-btn {
     margin-left: 20px;
 }
 .cache-actions input[type=radio] {
     font-size: 10px;
     display: inline-block;
     width: 15px;
     height: 12px;
     margin-left: 2px;
 }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="tab<?=$tab_index?>-buttons" style="position: relative;">

            <div class="form-gorup">
                <div class="mini-form">
                    <div class="h4 tab-header">Файл debug.txt</div>
                    <input
                        type="text" name="file_debug_name"
                        class="form-control form-control-sm w200" placeholder="Имя файла debug"
                        value="debug.txt"
                    >
                    <button name="get_file_debug" class="btn btn-sm btn-primary">Получить</button>
                    &nbsp;&nbsp;&nbsp;
                    <button name="clear_file_debug" class="btn btn-sm btn-danger">Очистить</button>
                    &nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <br>
            <div class="form-gorup">
                <h5>Прочее</h5>
                <div class="mini-form">
                    <button name="btn_push" class="btn btn-sm btn-primary">Нажать</button>
                    &nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <br>
            <div class="form-gorup">
                <div class="mini-form">
                    <div class="h4 tab-header">Очистить кэш .../runtime/cache</div>
                    <div class="cache-actions">
                        <div class="radio-inputs">
                            <label for="dir_frontend">frontend</label>
                            <input type="radio" name="dir_cache" id="dir_frontend" class="form-control w200" value="frontend" checked />
                            <br>
                            <label for="dir_backend">backend</label>
                            <input type="radio" name="dir_cache" id="dir_backend" class="form-control w200" value="backend" />
                        </div>
                        <div class="clear-btn">
                            <button name="clear_cache" class="btn btn-danger">Очистить</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <?=d::resDebug(false, 'res-tab' . $tab_index);?>
    </div>
</div>
<br><br>
<?php
$action = 'debug';
$this->registerJs(<<<JS
//JS
$(function(){});
var params = {};
params['action'] = '{$action}';
tabsAjax.call(this, '{$tab_index}', params);
JS
)
?>
