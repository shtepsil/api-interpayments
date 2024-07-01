<?php
/**
 * Класс для создания криптографических строк,
 * токенов и тд...
 * Тут взял идею:
 * https://snipp.ru/php/gen-token
 */

namespace shadow\helpers;

class CryptoHelper
{

    public static function createBearerToken(): string
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

}//Class
