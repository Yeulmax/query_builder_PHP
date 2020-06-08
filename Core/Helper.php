<?php

namespace App\Core;

class Helper
{
    public static function getUrl($controller, $action)
    {
       
        $listOfRoutes = getRoutes();

        foreach ($listOfRoutes as $url=>$route) {
            if ($route["controller"] == $controller && $route["action"]==$action) {
                return $url;
            }
        }

        die("Aucune correspondance pour la route");
    }
}
