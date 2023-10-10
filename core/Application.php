<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Respons $response;

    public static Application $app;
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Respons();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
            echo $this->router->resolve();
    }

}