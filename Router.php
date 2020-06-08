<?php
namespace App;

use App\Core\Exceptions\NotFoundException;
use PDOException;
use Throwable;

class Router {

    private $params = [];
    private $routes;
    private $routeCalled;

    public function __construct()
    {
        $this->getRoutes();
        $this->manageUrl();
    }

    public function getRoutes()
    {
        include('routes.php');
        $uriParams = explode('?', $_SERVER['REQUEST_URI'], 2);
        $this->routeCalled = $uriParams[0];
        if(isset($uriParams[1]))
            $this->params = $this->getParams($uriParams[1]);
        $this->routes =  getRoutes();
        return;
    }

    public function manageUrl()
    {
       

        if (!empty($this->routes[$this->routeCalled])) {
            $c =  'App\Controllers\\'.ucfirst($this->routes[$this->routeCalled]["controller"]."Controller");
            $a =  $this->routes[$this->routeCalled]["action"]."Action";
        
            
               try {
                    $controller = new $c();
               } catch( \Throwable $t) {
                    die("Le fichier controller n'existe pas");
               }
                //Vérifier que la class existe et si ce n'est pas le cas faites un die("La class controller n'existe pas")
                
                    
                    //Vérifier que la méthode existeet si ce n'est pas le cas faites un die("L'action' n'existe pas")
                    if (method_exists($controller, $a)) {
                        
                        //EXEMPLE :
                        //$controller est une instance de la class UserController
                        //$a = userAction est une méthode de la class UserController
                       try {
                            $controller->$a($this->params);
                       } catch(NotFoundException $e) {
                          
                           echo $e->getMessage();
                       }
                       
                       
                        
                    } else {
                        
                        die("L'action' n'existe pas");
                    }
                
        } else {
            // $errorsController = new ErrorContrller();
            // $errorsController->404()
            die("L'url n'existe pas : Erreur 404");
        }
        
    }


function getParams($params) {
    $explodedParams = explode('&', $params, 2);
    $result = [];
    foreach($explodedParams as $param) {
        $data = explode("=", $param);
        if(isset($data[1]))
        $result[$data[0]] =  $data[1];
    }
    return $result;
}
}