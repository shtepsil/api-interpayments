<?php

use yii\web\View;

$this->title = 'Debug скрипты';

$context = $this->context;
$tabs = require __DIR__ . '/tabs/tabs.php';

?>
<style>
    <?= $context->renderPartial('/debug/tabs/style.css') ?>
</style>
<div class="wrap-debug">

    <!-- Row -->
    <div class="row">

        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-default card-view">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">
                            Custom Tab 2
                        </h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <p class="text-muted">
                            Default version of tab add
                            <code>tab-struct custom-tab-2</code>
                            class.
                        </p>
                        <div class="tab-struct custom-tab-2 mt-40">
                            <ul role="tablist" class="nav nav-tabs" id="myTabs_15">

                                <? foreach ($tabs as $tab_index => $tab): ?>
                                    <li<?= (DEBUG_TAB_ACTIVE == $tab_index) ? ' class="active"' : '' ?> role="presentation">
                                        <a
                                            href="#<?= $tab['path'] ?>-content"
                                            data-toggle="tab"
                                            role="tab"
                                            aria-expanded="<?= (DEBUG_TAB_ACTIVE == $tab_index) ? 'true' : 'false' ?>"
                                        >
                                            <?= $tab_index ?>) <?= $tab['name'] ?>
                                        </a>
                                    </li>
                                <? endforeach ?>

                            </ul>
                            <div class="tab-content">

                                <? foreach ($tabs as $tab_index => $tab): ?>
                                    <div id="<?= $tab['path'] ?>-content" class="tab-pane fade <?= (DEBUG_TAB_ACTIVE == $tab_index) ? 'in active' : '' ?>">
                                        <?= $context->renderPartial('/debug/tabs/' . $tab['path'], [
                                            'tab_index' => $tab_index,
                                            'context' => $context
                                        ]) ?>
                                    </div>
                                <? endforeach ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Row -->

</div>
<?
$this->registerJs(<<<JS
function tabsAjax(tab, Data, stop) {
    $('.tab' + tab + '-buttons [class*=btn]').on('click', function(e) {
        e.stopPropagation();
        var _this = $(this),
        res = $('.res-tab' + tab),
        wrap = $('.wrap-debug'),
        load = wrap.find('.tab-content .layer-loading'),
        form_elements = _this.closest('.mini-form').find('input, textarea, select'),
        textarea = _this.closest('.mini-form').find('textarea'),
        name = _this.attr('name'),
        url = 'tab-debug-ajax',
        action = 'debug',
        method = 'post';

        if (_this.attr('data-ajax') == 'false') {
            return;
        }

        if (Data === undefined) { Data = {}; }

        // Если action передан со страницы
        if (Data['action'] !== undefined) {
            action = Data['action'];
        }

        // Если action передан с кнопки
        if (_this.attr('data-action') !== undefined) {
            action = _this.attr('data-action');
            // method = 'get';
            // Просто для показа в консоли
            Data['method'] = method;
        }

        Data['type'] = _this.attr('name');

        if (form_elements.length > 0) {
            if (form_elements.length > 1) {
                Data['inputs'] = form_elements.serializeArray();
            } else {
                switch (form_elements.attr('type')) {
                    case 'checkbox':
                        Data[form_elements.attr('name')] = form_elements.prop('checked');
                        break;
                    case 'text':
                        Data[form_elements.attr('name')] = form_elements.val();
                        break;
                    default:
                        Data[form_elements.attr('name')] = form_elements.val();

                }
            }
        }

        res.html('result' + tab);

        if (url === undefined || url === '') {
            $.growl.warning({title: 'Ошибка', message: 'Не передан url', duration: 5000});
            return;
        }

        /*
		$.growl.error({title: 'Ошибка', message: 'Всем привет я error', duration: 5000});
		$.growl.notice({title: 'Ошибка', message: 'Всем привет я notice', duration: 5000});
		$.growl.warning({title: 'Ошибка', message: 'Всем привет я warning', duration: 5000});
		*/

        // var csrf_param = $('meta[name=csrf-param]').attr('content');
        // var csrf_token = $('meta[name=csrf-token]').attr('content');
        // Data[csrf_param] = csrf_token;

        if (Data?.url) {
            // url, созданный на странице через Url::to() и переданный в params
            url = Data.url;
        } else {
            // местный url - и action либо местный либо переданный с кнопки
            url = '/admin/' + url + '?a=' + action;
        }
        
        Data['url'] = url;

        const param = yii.getCsrfParam(),
            token = yii.getCsrfToken();
        Data[param] = token;

        cl(Data);
        if(stop !== undefined) return;

        $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            cache: false,
            data: Data,
            beforeSend: function(){ load.fadeIn(100); }
        }).done(function(data){
            res.html('Done<br><pre>' + prettyPrintJson.toHtml(data) + '</pre>');

            if (_this.attr('data-log') !== undefined) {
                $('.log-html').html(data);
            }

            if (data !== null && typeof data.js !== 'undefined') {
                eval(data.js);
            }
        }).fail(function(data) {
            res.html('Fail<br>' + JSON.stringify(data));
        }).always(function() {
            load.fadeOut(100);
        });
    });
}

JS
    , View::POS_BEGIN
)
?>
