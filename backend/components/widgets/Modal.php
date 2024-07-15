<?php

namespace backend\components\widgets;

use common\components\Debugger as d;
//use backend\assets\ModalPluginAsset;
use shadow\helpers\Json;
use Yii;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Widget;
use yii\bootstrap\Html;


/**
 * Modal renders a modal window that can be toggled by clicking on a button.
 *
 * The following example will show the content enclosed between the [[begin()]]
 * and [[end()]] calls within the modal window:
 *
 * ~~~php
 * Modal::begin([
 *     'title' => '<h2>Hello world</h2>',
 *     'toggleButton' => ['label' => 'click me'],
 * ]);
 *
 * echo 'Say hello...';
 *
 * Modal::end();
 * ~~~
 *
 * @see http://getbootstrap.com/javascript/#modals
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Modal extends Widget
{
    const SIZE_LARGE = "modal-lg";
    const SIZE_SMALL = "modal-sm";
    const SIZE_DEFAULT = "";

    /**
     * @var string the title content in the modal window.
     */
    public $title;
    public $test = false;
    /**
     * @var string additional title options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $titleOptions = [];
    /**
     * @var array body options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $bodyOptions = [];
    public $body = '';

    /**
     * @var array Атрибуты самого плавающего модального окна
     */
    public $windowOptions = [];

    /**
     * Текст описания под заголовокм
     * @var string
     */
    public $description = '';
    /**
     * Атрибуты блока текста под заголовоком
     * @var
     */
    public $descriptionOptions;
    /**
     * @var string the footer content in the modal window.
     */
    public $footer;
    /**
     * @var string additional footer options
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @since 2.0.1
     */
    public $footerOptions = [];
    /**
     * @var string the modal size. Can be [[SIZE_LARGE]] or [[SIZE_SMALL]], or empty for default.
     */
    public $size;
    /**
     * @var array|false the options for rendering the close button tag.
     * The close button is displayed in the title of the modal window. Clicking
     * on the button will hide the modal window. If this is false, no close button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to '&times;'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     * Please refer to the [Modal plugin help](http://getbootstrap.com/javascript/#modals)
     * for the supported HTML attributes.
     */
    public $closeButton = [];
    /**
     * @var array the options for rendering the toggle button tag.
     * The toggle button is used to toggle the visibility of the modal window.
     * If this property is false, no toggle button will be rendered.
     *
     * The following special options are supported:
     *
     * - tag: string, the tag name of the button. Defaults to 'button'.
     * - label: string, the label of the button. Defaults to 'Show'.
     *
     * The rest of the options will be rendered as the HTML attributes of the button tag.
     * Please refer to the [Modal plugin help](http://getbootstrap.com/javascript/#modals)
     * for the supported HTML attributes.
     */
    public $toggleButton = false;

    public $toggleElement = false;


    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $this->initOptions();

        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::beginTag('div', $this->windowOptions) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-content']) . "\n";
        echo $this->renderTitle() . "\n";
        echo $this->renderDescription() . "\n";
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
//        echo "\n" . $this->renderBody();
        echo "\n" . $this->renderBody();
        echo "\n" . $this->renderFooter();
        echo "\n" . Html::endTag('div');
        echo "\n" . Html::endTag('div');
        echo "\n" . Html::endTag('div');

        $this->test = false;

        $this->registerPlugin('modal');
    }

    /**
     * Registers a specific Bootstrap plugin and the related events
     * @param string $name the name of the Bootstrap plugin
     */
    protected function registerPlugin($name)
    {
//        ModalPluginAsset::register($this->view);

        $id = $this->options['id'];

        if ($this->clientOptions !== false) {
            $this->clientOptions['block_id'] = "#$id";
            $options = empty($this->clientOptions) ? '' : Json::htmlEncode($this->clientOptions);
            $js = "jQuery('#$id').$name($options);";
            $this->view->registerJs($js);
        }

        $this->registerClientEvents();
    }

    /**
     * Renders the title HTML markup of the modal
     * @return string the rendering result
     */
    protected function renderTitle()
    {
        if ($this->title !== null) {
            Html::addCssClass($this->titleOptions, ['modal-header']);
            $title_content = $this->renderClose() . $this->title;
        } else {
            $title_content = $this->renderClose();
        }
        return Html::tag('div', $title_content, $this->titleOptions);
    }

    /**
     * Renders the title HTML markup of the modal
     * @return string the rendering result
     */
    protected function renderDescription()
    {
        if ($this->description != '') {
            Html::addCssClass($this->descriptionOptions, ['popupDescription']);
            return Html::tag('div', "\n" . $this->description . "\n", $this->descriptionOptions);
        } else {
            return null;
        }
    }

    /**
     * Renders the opening tag of the modal body.
     * @return string the rendering result
     */
    protected function renderBodyBegin()
    {
        return Html::beginTag('div', ['class' => 'modal-body']);
    }

    /**
     * Renders the closing tag of the modal body.
     * @return string the rendering result
     */
    protected function renderBodyEnd()
    {
        return Html::endTag('div');
    }

    protected function renderBody()
    {
        Html::addCssClass($this->bodyOptions, 'modal-body');
        return Html::tag('div', $this->body, $this->bodyOptions);
    }

    /**
     * Renders the HTML markup for the footer of the modal
     * @return string the rendering result
     */
    protected function renderFooter()
    {
//        if ($this->footer !== null) {
        $btn_cancel_default = [
            'tag' => 'button',
            'type' => 'button',
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal',
            'label' => 'Close'
        ];
        if (isset($this->footerOptions['btn_cancel'])) {
            $btn_cancel = ArrayHelper::remove($this->footerOptions, 'btn_cancel');
            $btn_cancel_default = ArrayHelper::merge($btn_cancel_default, $btn_cancel);
        }
        $this->footer = Html::tag(
            $btn_cancel_default['tag'],
            "\n" . $btn_cancel_default['label'] . "\n",
            $btn_cancel_default
        );
        $btn_confirm_default = [
            'tag' => 'button',
            'type' => 'button',
            'class' => 'btn btn-primary',
            'label' => 'Save changes'
        ];
        if (isset($this->footerOptions['btn_confirm'])) {
            $btn_confirm = ArrayHelper::remove($this->footerOptions, 'btn_confirm');
            $btn_confirm_default = ArrayHelper::merge($btn_confirm_default, $btn_confirm);
        }
        $this->footer .= Html::tag(
            $btn_confirm_default['tag'],
            "\n" . $btn_confirm_default['label'] . "\n",
            $btn_confirm_default
        );

//        } else {
//            $this->footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
//                <button type="button" class="btn btn-primary">Save changes</button>';
//        }
        Html::addCssClass($this->footerOptions, ['modal-footer']);
        return Html::tag('div', "\n" . $this->footer . "\n", $this->footerOptions);
    }

    /**
     * toggleElement - Это мой кастомный тег, который мне нужно сделать
     * в качестве клика для вызова модалки.
     * toggleButton - это массив для элемента button.
     * если в массиве есть label, то его можно задать как строкой, так и вложенными тегами.
     * @return string|null
     */
    protected function renderToggleButton()
    {
        if (
            (($toggleElement = $this->toggleButton) !== false)
            OR (($toggleElement = $this->toggleElement) !== false)
        ) {
            $tag = ArrayHelper::remove($toggleElement, 'tag', 'div');
            $label = ArrayHelper::remove($toggleElement, 'label', 'Show');
            if ($tag === 'button' && !isset($toggleElement['type'])) {
                $toggleElement['type'] = 'button';
            }
            $result = Html::tag($tag, "\n" . $label . "\n", $toggleElement);
        } else {
            $result = null;
        }

        return $result;
    }

    /**
     * Renders the close button.
     * @return string the rendering result
     */
    protected function renderClose()
    {
        if (($closeButton = $this->closeButton) !== false) {
            $tag = ArrayHelper::remove($closeButton, 'tag', 'button');
            $label = ArrayHelper::remove($closeButton, 'label', '<span aria-hidden="true">&times;</span>');
            if ($tag === 'button' && !isset($closeButton['type'])) {
                $closeButton['type'] = 'button';
            }
            return Html::tag($tag, $label, $closeButton) . "\n";
        } else {
            return null;
        }
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = ArrayHelper::merge([
            'class' => 'modal fade',
            'tabindex' => -1,
            'role' => 'dialog',
        ], $this->options);
//        Html::addCssClass($this->options, ['widget' => 'modal']);

//        $this->windowOptions = ArrayHelper::merge();

        if (is_array($this->windowOptions)) {
            $windowClasses = ['class' => 'modal-dialog'];
            $this->windowOptions = ArrayHelper::merge($this->windowOptions, ['role' => 'document']);
            if(isset($this->windowOptions['class'])){
                Html::addCssClass($windowClasses, $this->windowOptions['class']);
            }
            $this->windowOptions = ArrayHelper::merge($this->windowOptions, $windowClasses);
        }

        if ($this->clientOptions !== false) {
            $this->clientOptions = ArrayHelper::merge(['show' => false], $this->clientOptions);
        }

        if ($this->closeButton !== false) {
            $id = $this->options['id'];
            $this->closeButton = ArrayHelper::merge([
                'data-dismiss' => "#$id",
                'aria-label' => 'Close',
                'class' => 'close',
            ], $this->closeButton);
        }

        if ($this->toggleButton !== false) {
            $this->toggleButton = ArrayHelper::merge([
                'data-toggle' => 'popup',
            ], $this->toggleButton);
            if (!isset($this->toggleButton['data-target']) && !isset($this->toggleButton['href'])) {
                $this->toggleButton['data-target'] = '#' . $this->options['id'];
            }
        }

        /**
         * Атрибуты для элемента, по клику которого
         * произойдёт вызов модального окна
         */
        if ($this->toggleElement !== false) {
            $this->toggleElement = ArrayHelper::merge([
                'data-toggle' => 'modal',
            ], $this->toggleElement);
            if (!isset($this->toggleElement['data-target']) && !isset($this->toggleElement['href'])) {
                $this->toggleElement['data-target'] = '#' . $this->options['id'];
            }
            if(isset($this->toggleElement['tag']) AND $this->toggleElement['tag'] == 'a'){
                $this->toggleElement['href'] = '#' . $this->options['id'];
            }
        }
    }
}
