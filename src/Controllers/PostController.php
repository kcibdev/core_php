<?php

namespace Hp\LearningRoute\Controllers;

use Hp\LearningRoute\Common\Response;

class PostController
{
    public function post()
    {
        echo Response::reply("Hello Post of different work", 200);
    }
}
