<?php

namespace shadow\helpers;

use common\components\Debugger as d;
use Yii;
use yii\helpers\StringHelper;

class Inflector extends \yii\helpers\Inflector
{



    /**
     * Converts an ID into a CamelCase name.
     * Words in the ID separated by `$separator` (defaults to '-') will be concatenated into a CamelCase name.
     * For example, 'post-tag' is converted to 'PostTag'.
     * @param string $id the ID to be converted
     * @param string $separator the character used to separate the words in the ID
     * @return string the resulting CamelCase name
     */
    public static function lcFirstId2camel($id, $separator = '-')
    {
        $encoding = 'UTF-8';
        if (empty($id)) {
            return (string) $id;
        }
        $string = str_replace($separator, ' ', $id);
        $parts = preg_split('/(\s+\W+\s+|^\W+\s+|\s+)/u', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($parts as $key => $value) {
            if ($key == 0) $parts[$key] = $value;
            else $parts[$key] = static::mb_ucfirst($value, $encoding);
        }
        return str_replace(' ', '', implode('', $parts));
    }

    private static function encoding()
    {
        return isset(Yii::$app) ? Yii::$app->charset : 'UTF-8';
    }



    /**
     * This method provides a unicode-safe implementation of built-in PHP function `ucfirst()`.
     *
     * @param string $string the string to be proceeded
     * @param string $encoding Optional, defaults to "UTF-8"
     * @return string
     * @see https://www.php.net/manual/en/function.ucfirst.php
     * @since 2.0.16
     */
    public static function mb_ucfirst($string, $encoding = 'UTF-8')
    {
        $firstChar = mb_substr((string)$string, 0, 1, $encoding);
        $rest = mb_substr((string)$string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $rest;
    }

}//Class
