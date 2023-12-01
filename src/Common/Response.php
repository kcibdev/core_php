<?php

namespace Hp\LearningRoute\Common;

class Response
{
    /**
     * Generate a response to be sent back to the api.
     *
     * @param string $message contains what is happening with the response.
     * @param int $code The status code of the response.
     * @param mixed $data To pass any data or content.
     * @param bool $status The status of the response, if it's successful or not.
     *

     */
    public static function reply(?string $message, $code = SC200, $data = [], $status = true)
    {
        $res_data = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($res_data);
        exit;
    }
}
