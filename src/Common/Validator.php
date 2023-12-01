<?php

namespace Hp\LearningRoute\Common;

class Validator
{
    public static function email($mail)
    {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }
}
