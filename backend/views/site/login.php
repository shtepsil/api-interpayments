<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Login';
?>
<? Html::encode($this->title) ?>
<!-- Main Content -->
<div class="page-wrapper pa-0 ma-0 auth-page">
    <div class="container-fluid">
        <!-- Row -->
        <div class="table-struct full-width full-height">
            <div class="table-cell vertical-align-middle auth-form-wrap">
                <div class="auth-form  ml-auto mr-auto no-float">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="mb-30">
                                <h3 class="text-center txt-dark mb-10">Авторизация</h3>
                                <h6 class="text-center nonecase-font txt-grey">Администраторский кабинет</h6>
                            </div>
                            <div class="form-wrap">

                                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                                    <?= $form->field($model, 'username', [
                                        'template' => '<div class="form-group">{label}{input}{error}</div>',
                                        'labelOptions' => ['class' => 'pull-left control-label mb-10'],
                                        'inputOptions' => [
                                            'placeholder' => 'Введите логин'
                                        ]
                                    ])->textInput(['autofocus' => true]) ?>

                                    <?= $form->field($model, 'password', [
                                        'template' => '<div class="form-group">{label}<a class="capitalize-font txt-primary mb-10 pull-right font-12 forgot-password" href="#">Забыли пароль?</a>
                                            <div class="clearfix"></div>{input}{error}</div>',
                                        'labelOptions' => ['class' => 'control-label mb-10'],
                                        'inputOptions' => [
                                            'placeholder' => 'Введите пароль'
                                        ]
                                    ])->passwordInput() ?>

                                    <?= $form->field($model, 'rememberMe')->checkbox([
                                        'template' => '<div class="form-group"><div class="checkbox checkbox-primary pr-10 pull-left">{input}{label}</div><div class="clearfix"></div></div>',
                                        'checked' => false
                                    ]) ?>

                                    <div class="form-group text-center">
                                        <?= Html::submitButton('Авторизоваться', ['class' => 'btn btn-info btn-rounded', 'name' => 'login-button']) ?>
                                    </div>

                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Row -->
    </div>

</div>
<!-- /Main Content -->
<?php
$this->registerJs(<<<JS
    $('.forgot-password').on('click', () => {
        swal('Увы', 'Такой функционал пока недоступен :(', 'error'); 
    });
JS
);
