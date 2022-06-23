<?php
class Route{
   function loadPage($db, $controllerName, $actionName = 'index'){
       include_once 'app/Controllers/IndexController.php';
       include_once 'app/Controllers/UsersController.php';
       include_once 'app/Controllers/FilmsController.php';
       include_once 'app/Controllers/ActorsController.php';

       switch ($controllerName) {
           case 'users':
                $controller = new UsersController($db);
                break;
            case 'films':
                $controller = new FilmsController($db);
                break;
            case 'actors':
                $controller = new ActorsController($db);
                break;
           default:
                $controller = new IndexController($db);
       }
       // запускаємо необхідний метод
       $controller->$actionName();
   }
}
