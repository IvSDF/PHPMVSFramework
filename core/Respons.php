<?php

namespace app\core;

class Respons
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
}