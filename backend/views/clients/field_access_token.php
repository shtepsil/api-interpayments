{label}
<div class="col-md-10">
    <div class="input-group wrap-token">
        <div class="input-token">{input}</div>
        <div class="wrap-btn-create-token">
            <button type="button" class="btn btn-primary btn-create-token">Создать<br>новый токен</button>
        </div>
    </div>
    {error}
</div>
<?php

use yii\web\View;

$this->registerJs(<<<JS

$('.wrap-token button').on('click', function () {
    const inputToken = $('#clients-access_token');

    if (inputToken.val() === '') {
        inputToken.val(createBearerToken());
    } else {
        swal({   
            title: 'Вы действительно хотите пересоздать токен?',   
            text: 'Существующий токен будет удалён безвозвратно!',   
            type: 'warning',   
            showCancelButton: true,   
            confirmButtonColor: '#e69a2a',   
            confirmButtonText: 'Пересоздать',   
            cancelButtonText: 'Отмена',   
            closeOnConfirm: false 
        }, function() {
            inputToken.val(createBearerToken());
            swal('Успешно', 'Новый токен создан', 'success');
        });
    }
});

JS, View::POS_END
);
