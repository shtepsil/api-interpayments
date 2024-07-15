<?php

use common\components\Debugger as d;
use common\models\User;
use shadow\helpers\SArrayHelper;
use shadow\widgets\AdminActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;

$user_id = 19299;
$user_id = 21277;
$context = $this->context;
$attrs = array_keys((new User)->getAttributes());
$userAttributes = [];
foreach ($attrs as $attr) {
    $userAttributes[$attr] = $attr;
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="tab<?=$tab_index?>-buttons" style="position: relative;">

            <div class="form-gorup">

                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#other-content" data-toggle="tab">Прочее</a>
                    </li>
                    <li>
                        <a href="#unloading-content" data-toggle="tab">Выгрузка</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Мои скрипты -->
                    <div id="other-content" class="tab-pane fade in active">

                        <div class="mini-form">
                            <input
                                type="text" name="user_data"
                                class="form-control w150 float-left" placeholder="ID или телефон"
                                value="<?=$user_id?>"
                            >
                            <button name="get_user" class="btn btn-sm btn-primary">Получить</button>
                            &nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="mini-form">
                            <?=Html::dropDownList('field_name', null, $userAttributes, ['class' => 'form-control w150 float-left'])?>
                            <input
                                type="text" name="field_value"
                                class="form-control w150 float-left" placeholder="Новое значение"
                                value=""
                            >
                            <button name="set_field" class="btn btn-sm btn-primary">Установить</button>
                            &nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="mini-form">
                            <?php

                            //                            $form_t = AdminActiveForm::begin([
                            //                                'action' => isset($form_action) ? $form_action : '',
                            //                                'enableAjaxValidation' => false,
                            //                                'options' => [],
                            //                                'fieldConfig' => [
                            //                                    'options' => ['class' => 'form-group simple'],
                            //                                    'template' => "{label}<div class=\"col-md-10\">{input}\n{error}</div>",
                            //                                    'labelOptions' => ['class' => 'col-md-2 control-label'],
                            //                                ],
                            //                            ]);
                            //                            $u = \common\models\User::findOne(21277);
                            //                            echo $form_t->field($u, 'payment_types')->widget(\kartik\select2\Select2::className(), [
                            //                                'name' => 'payment_types_widget',
                            //                                'value' => 'cash',
                            //                                'data' => [
                            //                                    'cash' => 'Наличные',
                            //                                    'online' => 'Онлайн оплата',
                            //                                    'cards' => 'Банковской картой',
                            //                                    'invoice' => 'Счёт для оплаты',
                            //                                    'test' => 'Тестовая оплата',
                            //                                ],
                            //                                'options' => ['multiple' => true, 'placeholder' => ''],
                            //                            ]);

                            //                            $form_t::end();

                            ?>
                        </div>

                        <div class="mini-form dn">
                            <div class="inputs">
                                <input
                                    type="text" name="field_name"
                                    class="form-control w150 float-left" placeholder="field_name"
                                    value="id"
                                    style="margin-bottom:-1px;"
                                >
                                <input
                                    type="text" name="field_value"
                                    class="form-control w150 float-left" placeholder="field_value"
                                    value=""
                                >
                            </div>
                            <button name="get_user" class="btn btn-sm btn-primary">Получить</button>
                            &nbsp;&nbsp;&nbsp;
                            <button name="set_wholesale" class="btn btn-sm btn-primary">Установить</button>
                            &nbsp;&nbsp;&nbsp;
                            <button name="delete_user" class="btn btn-sm btn-danger" disabled>Удалить</button>
                            &nbsp;&nbsp;&nbsp;
                        </div>

                    </div>
                    <!-- /мои скрипты -->

                    <!-- Выгрузка -->
                    <div id="unloading-content" class="tab-pane fade">

                        <form action="">
                            <div class="mini-form">
                                <div class="h5 tab-header">Выгрузка всех клиентов делавших заказы</div>
                                <div class="input-group">
                                    &nbsp;&nbsp;<label for="part_2">Загрузить вторую часть</label>
                                    <input type="checkbox" id="part_2"
                                           name="part_2" class="form-control"
                                           style="width: 16px;position:relative;top:-9px;"
                                    ><br><br>
                                    <button class="btn btn-sm btn-default" type="submit" data-ajax="false"
                                            name="export_users"><i class="fa fa-upload"></i>
                                        Выгрузка
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="mini-form">
                                <div class="h5 tab-header">Выгрузка клиентов делавших заказы с указанными товарами</div>
                                <div class="input-group w100proc">
                                    <input
                                        type="text" name="item_name"
                                        class="form-control w100proc" placeholder="Часть или точное наименование товара"
                                        value=""
                                    >
                                    <button class="btn btn-sm btn-default" type="submit" data-ajax="false"
                                            name="export_users_orders_by_items"><i class="fa fa-upload"></i>
                                        Выгрузить в Excel
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- /выгрузка -->

                </div>

            </div>
            <br>

        </div>
        <br><br>
        <?=d::resDebug(false, 'res-tab' . $tab_index);?>
    </div>
</div>
<br><br>
<?php
$action = 'user';
$this->registerJs(<<<JS
//JS
$(function(){});
var params = {};
params['action'] = '{$action}';
tabsAjax('{$tab_index}', params);

JS
)
?>
