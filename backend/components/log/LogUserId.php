<?php

namespace backend\components\log;

use common\components\Debugger as d;
use shadow\helpers\StringHelper;
use Yii;
use yii\log\Logger;

class LogUserId
{
    /**
     * Logs a debug message.
     * Trace messages are logged mainly for development purposes to see
     * the execution workflow of some code. This method will only log
     * a message when the application is in debug mode.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as an array.
     * @param string $category the category of the message.
     * @since 2.0.14
     */
    public static function debug($message, $category = 'application', $user_id = null)
    {
        if (YII_DEBUG) {
            if ($user_id) {
                static::setUserIdDirectory('debug', $user_id);
            }
            Yii::getLogger()->log($message, Logger::LEVEL_TRACE, $category);
        }
    }

    /**
     * Alias of [[debug()]].
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as an array.
     * @param string $category the category of the message.
     * @deprecated since 2.0.14. Use [[debug()]] instead.
     */
    public static function trace($message, $category = 'application', $user_id = null)
    {
        if ($user_id) {
            static::setUserIdDirectory('trace', $user_id);
        }
        static::debug($message, $category, $user_id);
    }

    /**
     * Logs an error message.
     * An error message is typically logged when an unrecoverable error occurs
     * during the execution of an application.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as an array.
     * @param string $category the category of the message.
     */
    public static function error($message, $category = 'application', $user_id = null)
    {
        if ($user_id) {
            static::setUserIdDirectory('error', $user_id);
        }
        Yii::getLogger()->log($message, Logger::LEVEL_ERROR, $category);
    }

    /**
     * Logs a warning message.
     * A warning message is typically logged when an error occurs while the execution
     * can still continue.
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as an array.
     * @param string $category the category of the message.
     */
    public static function warning($message, $category = 'application', $user_id = null)
    {
        if ($user_id) {
            static::setUserIdDirectory('warning', $user_id);
        }
        Yii::getLogger()->log($message, Logger::LEVEL_WARNING, $category);
    }

    /**
     * Logs an informative message.
     * An informative message is typically logged by an application to keep record of
     * something important (e.g. an administrator logs in).
     * @param string|array $message the message to be logged. This can be a simple string or a more
     * complex data structure, such as an array.
     * @param string $category the category of the message.
     */
    public static function info($message, $category = 'application', $user_id = null)
    {
        if ($user_id) {
            static::setUserIdDirectory('info', $user_id);
        }
        Yii::getLogger()->log($message, Logger::LEVEL_INFO, $category);
    }

    private static function setUserIdDirectory($logFile = 'app.log', $user_id = null)
    {
        $targets = Yii::$app->log->targets;
        if ($user_id AND is_array($targets) AND count($targets)) {
            foreach ($targets as $target_key => $target) {
                $targetLogFile = Yii::getAlias('@logs/' . $user_id . '/' . $logFile . '.log');
//                d::ajax($targetLogFile);
                Yii::$app->log->targets[$target_key]->logFile = $targetLogFile;
            }
        }
    }

}//Class
