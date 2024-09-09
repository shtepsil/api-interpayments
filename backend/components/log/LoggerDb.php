<?php

namespace backend\components\log;

use common\components\Debugger as d;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\log\Target;

abstract class LoggerDb extends Target
{

    public $message_log_separator =
        "\n\n------------\n";

    /**
     * Generates the context information to be logged.
     * The default implementation will dump user information, system variables, etc.
     * @return string the context information. If an empty string, it means no context information.
     */
    protected function getContextMessage()
    {
        $context = ArrayHelper::filter($GLOBALS, $this->logVars);
        foreach ($this->maskVars as $var) {
            if (ArrayHelper::getValue($context, $var) !== null) {
                ArrayHelper::setValue($context, $var, '***');
            }
        }
        $result = [];
        foreach ($context as $key => $value) {
            $result[] = "\${$key} = " . VarDumper::dumpAsString($value);
        }

        return implode("\n\n", $result) . $this->message_log_separator;
    }
}//Class
