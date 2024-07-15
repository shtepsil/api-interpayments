<?php

namespace backend\controllers;

use common\components\Debugger as d;
use common\models\User;
use backend\assets\AppAsset;
use shadow\widgets\AdminActiveForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class MainController extends Controller
{

    public $model;
    public $AppAsset;
    public $url = [
        'back' => ['site/index'],
        'control' => []
    ];
    public $breadcrumb = [];

    public function init()
    {
        parent::init();
        $this->AppAsset = AppAsset::register($this->view);

    }

    public function actionControl()
    {
        $item = $this->model;
        if ($id = \Yii::$app->request->get('id')) {
            $item = $item->findOne($id);
        }
        $data['item'] = $item;
        if ($data['item']) {
            return $this->render('//control/form', $data);
        } else {
            return false;
        }
    }

    /**
     * @return Response
     */
    public function actionSave()
    {
//        sleep(5);
        $record = $this->model;
        $post = Yii::$app->request->post();
//        d::ajax($record::className());
//        d::ajax('AdminController->actionSave');
//        d::ajax($_FILES);
//        d::ajax($post);
        if ($id = Yii::$app->request->post('id')) {
            $record = $record->findOne($id);
        }
        if (isset($post['scenario'])) {
            $record->scenario = $post['scenario'];
        }
//        d::pex($record);
//        d::ajax($record);
//        d::ajax(get_class($record));
        if ($record->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                // Для отправки пуш уведомлений сделаем другие условия
                if(isset($post['onesignal_notification'])){
                    $result = Yii::$app->one_signal->start($post['onesignal_notification']);
                }else{
                    $record->on($record::EVENT_AFTER_VALIDATE, [$record, 'validateAll']);
                    if ($errors = AdminActiveForm::validate($record)) {
                        $result['errors'] = $errors;
                    } else {
//                        d::ajax('Сохраняем');
                        $event = $record->isNewRecord ? $record::EVENT_BEFORE_INSERT : $record::EVENT_BEFORE_UPDATE;
                        $record->on($event, [$record, 'saveAll']);
                        $event_clear = $record->isNewRecord ? $record::EVENT_AFTER_INSERT : $record::EVENT_AFTER_UPDATE;
                        $record->on($event_clear, [$record, 'saveClear']);

                        $save = $record->save(false);
                        if ($save) {

//                        if (1) {
//                            if (Yii::$app->request->post('commit') == 1) {
//                                $result['url'] = Url::to($this->url['back']);
//                            } else {
                            if (Yii::$app->db->getLastInsertID() != '0') {
                                $url = $this->url['index'];
                                $result['url'] = Url::to($url);
                            } else {
                                $url = $this->url['control'];
                                $url['id'] = $record->id;
                                $result['url'] = Url::to($url);
                            }
//                            }
                            $result['set_value']['id'] = $record->id;
                            $result['message']['success'] = 'Сохранено!';
                        } else {
                            $result['message']['error'] = 'Произошла ошибка!';
                        }
                    }
                }
                return $result;
            } else {
                $record->validate();
            }
        }
        if (!Yii::$app->request->isAjax) {
            return $this->goBack();
        }
    }

    /**
     * @param $id
     * @return array|Response
     */
    public function actionDeleted($id)
    {
        /**
         * @var $item \yii\db\ActiveRecord
         */
        $result = [];
        if(
            Yii::$app->user->can('admin')
            || Yii::$app->user->can('copywriter')
            || Yii::$app->user->can('manager')
        ){
            $item = $this->model->findOne($id);
            $item->on($item::EVENT_BEFORE_DELETE, [$item, 'saveClear']);

            if ($item->getAttribute('not_delete')) {
                $result['error'] = 'Данная запись защищена от удаления!';
            } elseif ($item->hasAttribute('deleted_at')) {
                $item->deleted_at = time();
                if($item->save(false) ) {
                    $result['success'] = 'Запись успешно удаленна!';
                } else{
                    $result['error'] = 'Произошла ошибка на стороне сервера!';
                }
            } else {
                if($item->delete()){
                    $result['success'] = 'Запись успешно удаленна!';
                }else{
                    $result['error'] = 'Произошла ошибка на стороне сервера!';
                }
            }
        }else{
            $result['error'] = 'У вас нет прав!';
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (isset($result['success'])) {
                \Yii::$app->session->setFlash('success', $result['success']);
            }
            return $result;
        }else{
            if ($result) {
                foreach ($result as $key => $value) {
                    \Yii::$app->session->setFlash($key, $value);
                }
            }
            return $this->goBack();
        }
    }

}//Class
