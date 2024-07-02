{label}
<div class="col-md-10">
    <div class="input-group wrap-password">
        <div class="input-password">{input}</div>
        <div class="wrap-view-password">
            <i class="fa fa-eye hidden" data-type="hide"></i>
            <i class="fa fa-eye-slash" data-type="show"></i>
        </div>
    </div>
    {error}
</div>
<?php

use yii\web\View;

$this->registerJs(<<<JS

$('.wrap-password .fa').on('click', function () {
    const _this = $(this),
        inputPass = $('#clients-password');

    if (_this.attr('data-type') == 'show') {
        inputPass.attr('type', 'text');
        _this.addClass('hidden').promise().done(() => {
            _this.prev().removeClass('hidden');
        });
        
    } else {
        inputPass.attr('type', 'password');
        _this.addClass('hidden').promise().done(() => {
            _this.next().removeClass('hidden');
        });
    }
});

JS, View::POS_END
);
