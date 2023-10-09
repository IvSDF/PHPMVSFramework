<?php

namespace app\core;

class Router
{
    public Request $request;
    protected array $routs = [];

    public function __construct(\app\core\Request $request)
    {
        $this->request = $request;
    }

    public function get($path, $callback)
    {
        $this->routs['get'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routs[$method][$path] ?? false;

        if ($callback === false) {
            echo "NOT FOUND 404";
            exit;
        }

        echo call_user_func($callback);
    }

}