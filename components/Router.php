<?php

class Router
{

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include ($routesPath);
    }

    // Return type
    private function getURI()
    {
        if (! empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                $segments = explode('/', $internalRoute);
                
                if (in_array('Guestbook', $segments)) {
                    unset($segments[array_search('Guestbook', $segments)]);
                    
                    if (in_array('index.php', $segments)) {
                        unset($segments[array_search('index.php', $segments)]);
                    }
                }
                
                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);
                
                $actionName = 'action' . ucfirst(array_shift($segments));
                
                $parameters = $segments;
                // ���������� ���� ������-�����������
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                
                if (file_exists($controllerFile)) {
                    include_once ($controllerFile);
                }
                // ������� ������, ������� ����� (�.�. action)
                $controllerObject = new $controllerName();
                
                $result = call_user_func_array(array(
                    $controllerObject,
                    $actionName
                ), $parameters);
                if ($result != null) {
                    break;
                }
            }
        }
    }
}
