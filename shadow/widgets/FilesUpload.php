<?php
/**
 * Created by PhpStorm.
 * Project: yii2-cms
 * User: lxShaDoWxl
 * Date: 30.04.15
 * Time: 13:45
 */

namespace shadow\widgets;


use common\models\ItemImg;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

class FilesUpload extends Widget {
    public $params = [];
    public $title = 'Файлы';
    public $options = [];
    public $name = 'file';
    public $filters = ['imageFilter' => []];
    public $value = [];
    public $isImg = false;
    public $isSort = false;
    public $isName = false;
    public $isVisible = false;
    public $visibilityUrl = '';

    public function run()
    {
        if(isset($this->params['id'])){
            $id = $this->params['id'];
        }else{
            $id = $this->getId();
            $this->params['id'] = $id;
        }
        $options=[
            'url'=> Url::to(['site/upload','temp'=>$this->name]),
            'alias'=>$this->name,
            'formData'=>[
                [ '_csrf'=>Yii::$app->request->getCsrfToken()]
            ]
        ];
        if (isset($this->options)) {
            $this->options = ArrayHelper::merge($options, $this->options);
        } else {
            $this->options = $options;
        }
        $this->params['options'] = Json::encode($this->options);
        $this->params['files'] = Json::encode($this->value);
        $this->params['filters'] = Json::encode($this->filters);
        if(isset($this->filters['imageFilter'])){
            $this->isImg = true;
        }
        return $this->render('filesUpload', $this->params);
    }
}