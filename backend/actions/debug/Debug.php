<?php

namespace backend\actions\debug;

use common\components\Debugger as d;
use common\models\User as ModelUser;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;
use backend\components\migration\m240708171626createwhitelisttable;

class Debug extends Main
{

    public function run()
    {
        $this->post = d::post();
//        d::debugAjax($this->post);
        $response = 'Debug->run() ничего не произошло.';
        switch($this->post['type']){
            case 'btn_push':
                $response = $this->test();
                break;
            case 'clear_cache':
                $response = $this->clearCache();
                break;
            default:
                $response = 'Debug->run()->switch:default';
        }
        return $response;
    }

    /*
     * Кнопка "Нажать"
     */
    public function test()
    {
        return 'Debug->test()';
    }

    public function clearCache()
    {
        $dirPath = Yii::getAlias('@' . $this->post['dir_cache']) . '/runtime/cache';

        // Рекурсивное удаление файлов и директорий
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }

        return 'Кэш очищен';
    }

    private function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}//Class
