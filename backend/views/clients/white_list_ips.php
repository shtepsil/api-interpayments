<?php

// Коммент

use backend\components\helpers\Html;
use backend\components\widgets\Modal;
use common\components\Debugger as d;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\web\View;

?>
<div class="wrap-ips-list">
    <div class="col-md-2" style="text-align: right;color: white;"><?= $title ?></div>
    <div class="col-md-10">
        <div class="input-group" style="width: 100%">
            <div class="list-group ips-list <?=(count($data_ips) == 0 ? 'hidden' : '')?>">
            <?if (count($data_ips) > 0) :?>
                <?foreach ($data_ips as $ip_id => $ip) :?>
                    <div class="list-group-item ip-item">
                        <i class="fa fa-sitemap"></i>&nbsp;&nbsp;&nbsp;<?= $ip?> <i class="zmdi zmdi-delete txt-danger ip-remove" data-id="<?= $ip_id?>"></i>
                    </div>
            <?endforeach;endif;?>
            </div>
            <div class="ip-empty <?=(count($data_ips) == 0 ? '' : 'hidden')?>">Добавьте IP адрес по кнопке ниже<br><br></div>
            <div class="wrap-btn-add-ip">
                <button type="button" class="btn btn-primary add-ip" data-bs-toggle="modal" data-bs-target="#modalAddIp">Добавить IP</button>
            </div>
        </div>
    </div>
    <?=Modal::widget([
        'id' => 'modalAddIp',
        'windowOptions' => [
            'class' => 'modal-md'
        ],
        'footerOptions' => [
            'btn_cancel' => [
                'label' => 'Отмена',
                'class' => 'btn btn-sm btn-warning'
            ],
            'btn_confirm' => [
                'label' => 'Добавить',
                'class' => 'btn btn-sm btn-primary btn-ad-ip',
            ]
        ],
        'title' => 'Добавить новый разрешённый IP',
        'body' => Html::textInput('new_ip', '168.192.0.1', [
            'class' => 'form-control', 'placeholder' => 'Введите IP'
        ])// . d::res()
    ])?>
</div>
<?php
$urlAddIp = Url::to(['white-list/save']);
$urlRemoveIp = Url::to(['white-list/remove']);
$this->registerJs(<<<JS

var modal = $('#modalAddIp', '.wrap-ips-list');

modal.on('hidden.bs.modal', function() {
    $('[name=new_ip]', '.wrap-ips-list').val('168.192.0.1');
});
modal.on('show.bs.modal', function() {
    
});

// setTimeout(() => {modal.modal('show')}, 500);

// Добавление нового IP
$('.btn-ad-ip', '.wrap-ips-list').on('click', function () {
    const newIp = $('[name=new_ip]', '.wrap-ips-list'),
        listIps = $('.ips-list', '.wrap-ips-list'),
        ipEmpty = $('.ip-empty', '.wrap-ips-list'),
        tabCountIps = $('.tab-count-ips'),
        numCount = +tabCountIps.html();

    if (newIp.val() == '') {
        t.warning('Заполните поле');
        return;
    }

    send('{$urlAddIp}', {
        WhiteList: {
            client_id: '{$client_id}',
            ip: newIp.val(),
        }
    }, true).then(
        (data) => {
            res(data);
            if (data?.message?.success) {
                // cl(data.message.success);
                t.success(data.message.success);
                ipEmpty.addClass('hidden');
                listIps.removeClass('hidden');
                listIps.append('\
    <div class="list-group-item ip-item">\
        <i class="fa fa-sitemap"></i>\
        &nbsp;&nbsp;&nbsp;' + newIp.val()
        + ' <i class="zmdi zmdi-delete txt-danger ip-remove" data-id="' + data.ip_id + '"></i>\
    </div>\
                ');
                tabCountIps.html(numCount + 1);
                modal.modal('hide');
            } else {
                cl(data.message.error);
                t.warning(data.message.error);
            }
        },
        (data) => {
            cl(data);
            t.error('Ошибка сервера');
        }
    );
});

// Удаление IP
$('.wrap-ips-list').on('click', '.ip-remove', function () {
    const _this = this;

    swal({   
        title: 'Вы действительно хотите<br>удалить IP адрес?',
        html: ` `,
        type: 'warning',   
        showCancelButton: true,   
        confirmButtonColor: '#e69a2a',   
        confirmButtonText: 'Удалить',   
        cancelButtonText: 'Отмена',   
        closeOnConfirm: false 
    }, function() {
        deleteIp.call(_this);
        // swal('Успешно', 'Новый токен создан', 'success');
    });
});

function deleteIp() {
    const _this = $(this),
        listIps = $('.ips-list', '.wrap-ips-list'),
        ipEmpty = $('.ip-empty', '.wrap-ips-list'),
        tabCountIps = $('.tab-count-ips'),
        numCount = +tabCountIps.html();

    send('{$urlRemoveIp}', {
        id: _this.attr('data-id'),
    }, true).then(
        (data) => {
            res(data);
            if (data?.message?.success) {
                t.success(data.message.success);
                _this.parent().hide(100, function () {
                    _this.parent().remove();
                    if (listIps.find('.ip-item').length == 0) {
                        ipEmpty.removeClass('hidden');
                        listIps.addClass('hidden');
                        // tabCountIps.html(numCount - 1);
                    }
                    tabCountIps.html(listIps.find('.ip-item').length);
                });
                swal.close();
            } else {
                cl(data.message.error);
                t.warning(data.message.error);
            }
        },
        (data) => {
            cl(data);
            t.error('Ошибка сервера');
        }
    );
}

JS, View::POS_END
);
