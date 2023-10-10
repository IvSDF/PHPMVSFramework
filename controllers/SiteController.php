<?php

namespace app\controllers;
use app\core\Application;

class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "Name_Params"
        ];
        return $this->render('home', $params);
    }
    public function contact()
    {
        return $this->render('contact');
    }
    public function handleContact()
    {
        Application::$app->request->getBody();
        return 'Handling submitted data';
    }
}