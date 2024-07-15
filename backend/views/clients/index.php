<?php

use common\components\Debugger as d;
use common\models\Clients;
use yii\helpers\Url;

$this->title = 'Список клиентов';

/** @var Clients $clients */
/** @var Clients $client */

?>
<? d::res() ?>
<!-- Title -->
<div class="row heading-bg">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-dark"><?= $this->title?></h5>
    </div>
    <!-- Breadcrumb -->
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?= Url::to(['site/index'])?>">Главная</a></li>
            <li class="active"><span><?= $this->title?></span></li>
        </ol>
    </div>
    <!-- /Breadcrumb -->
</div>
<!-- /Title -->

<!-- Row -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="">
                    <h6 class="panel-title txt-dark">Таблица клиентов</h6>
                </div>
                <br>
                <div class="create-new-client">
                    <a href="<?= Url::to(['clients/control'])?>" class="btn btn-primary btn-sm">Создать нового клиента</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table id="datable_1" class="table table-hover display pb-30 table-clients">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col width="395">
                                    <col width="80">
                                    <col>
                                    <col>
                                    <col width="110">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>Имя клиента(аккаунта)</th>
                                    <th>Описание</th>
                                    <th>Токен</th>
                                    <th>Что то</th>
                                    <th>Дата регистрации</th>
                                    <th>Баланс</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
<!--                                <tfoot>-->
<!--                                <tr>-->
<!--                                    <th>Имя клиента(аккаунта)</th>-->
<!--                                    <th>Описание</th>-->
<!--                                    <th>Токен</th>-->
<!--                                    <th>Что то</th>-->
<!--                                    <th>Дата регистрации</th>-->
<!--                                    <th>Баланс</th>-->
<!--                                    <th>Действия</th>-->
<!--                                </tr>-->
<!--                                </tfoot>-->
                                <tbody><?
                                foreach ($clients as $client):?>
                                    <tr class="tr-item">
                                        <td class="td-name">
                                            <a href="<?= Url::to(['clients/control', 'id' => $client->id])?>">
                                                <?= $client->id?>: <?= $client->name?>
                                            </a>
                                        </td>
                                        <td><?= $client->description?></td>
                                        <td>
                                            <div class="wrap-token">
                                                <div class="token-string"><?= $client->access_token?></div>
                                                <div class="btn btn-primary btn-outline btn-sm copy"><i class="fa fa-files-o" data-type="token" aria-hidden="true"></i>&nbsp;&nbsp;Скопировать токен</div>
                                            </div>

                                        </td>
                                        <td></td>
                                        <td><?= date('d.m.Y', $client->created_at)?></td>
                                        <td></td>
                                        <td class="td-actions">
                                            <div class="edit"><a href="<?= Url::to(['clients/control', 'id' => $client->id])?>"><i class="zmdi zmdi-edit txt-warning"></i></a></div>
                                            <div class="delete"><i class="zmdi zmdi-delete txt-danger client-remove" data-id="<?= $client->id?>"></i></div>
                                        </td>
                                    </tr>
                                <?endforeach;?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Row -->
<?php
$clientRemove = Url::to(['clients/remove-item']);
$this->registerJs(<<<JS
$('.client-remove', '.table-clients').on('click', function () {
    const _this = this;
    swal({   
        title: 'Вы действительно хотите удалить клиента?',
        type: 'warning',   
        showCancelButton: true,   
        confirmButtonColor: '#EB4C42',   
        confirmButtonText: 'Удалить',   
        cancelButtonText: 'Отмена',   
        closeOnConfirm: false 
    }, function() {
        clientRemove.call(_this);
    });
});

// Копирование информации в буфер обмена
$('.copy', '.wrap-token').on('click', function() {
    let _this = $(this),
        text = _this.closest('.wrap-token').find('.token-string').text();

    if (text != '') {
        copyToClipboard(text);
        t.success('Токен скопирован');
    } else {
        t.warning('Нет информации для копирования');
    }
});

function clientRemove() {
    const _this = $(this),
        trItem = _this.closest('.tr-item'),
        itemId = _this.attr('data-id'),
        res = $('.res');

    $.ajax({
        url: '{$clientRemove}',
        type: 'get',
        dataType: 'json',
        cache: false,
        data: {id: itemId},
        beforeSend: () => {
            loader.show();
        }
    }).done((data) => {
        res.html('<pre>' + prettyPrintJson.toHtml(data) + '</pre>');
        if (data?.message) {
            t.success(data.message);
            trItem.hide(600, function () {
                trItem.remove();
            });
        } else {
            t.warning(data.error);
        }
    }).fail((data) => {
        res.html(JSON.stringify(data));
        t.error('Произошла ошибка на стороне сервера');
    }).always(() => {
        loader.hide();
        swal.close();
    });
}
JS
);
