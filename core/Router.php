<?php

namespace app\core;

use app\controllers\SiteController;

class Router
{
    public Request $request;
    public Respons $response;
    protected array $routs = [];


    /**
     * @param Request $request
     * @param Respons $response
     */
    public function __construct(Request $request, Respons $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routs['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routs['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callback = $this->routs[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback);
    }

    public function renderView($view, $params = [])
    {
        $layoutsContact = $this->layoutsContent();
        $viewContact = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContact, $layoutsContact);
    }

    private function renderContent($viewContent)
    {
        $layoutsContent = $this->layoutsContent();
        return str_replace('{{content}}', $viewContent, $layoutsContent);
    }

    protected function layoutsContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();

    }

}