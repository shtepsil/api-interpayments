<?php

namespace api\components;

class Response
{

    /**
     * @param mixed $data
     * @return array
     */
    public static function success($data): array
    {
        $result = [
            'success' => true,
            'message' => $data['message'] ?? $data,
        ];

        if (isset($data['status'])) {
            $result['status'] = $data['status'];
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return array
     */
    public static function error($data): array
    {
        $result = [
            'success' => false,
            'message' => $data['message'] ?? $data,
        ];

        if (isset($data['status'])) {
            $result['status'] = $data['status'];
        }

        return $result;
    }

}//Class
