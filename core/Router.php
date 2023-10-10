<?php

namespace app\core;

use app\controllers\SiteController;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routs = [];


    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
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

        $controller = new $callback[0];

        return $controller->{$callback[1]}();
    }

    private function renderView($view)
    {
        $layoutsContact = $this->layoutsContent();
        $viewContact = $this->renderOnlyView($view);
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

    protected function renderOnlyView($view)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();

    }

}