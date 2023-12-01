<?php

namespace Hp\LearningRoute\Common;

use Hp\LearningRoute\Common\Response;

class Functions
{
    public static function dump(...$values)
    {
        foreach ($values as $var) {
            var_dump($var);
        }
        die();
    }

    public static function abort($message, int $code = SC404)
    {
        echo Response::reply($message, $code, [], false);

        die() or exit;
    }

    public static function secure_rand($input, $strength = 6)
    {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public static function remove_data($data, ...$un)
    {
        foreach ($un as $key) {
            unset($data[$key]);
        }
        return $data;
    }
}
