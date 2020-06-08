<?php
namespace App\Controllers;

class DefaultController
{
    public function defaultAction()
    {

        //Récupéré depuis la bdd
        $firstname = "Yves";
        echo 'Default default';
        //View dashboard sur le template back
       // $myView = new View("dashboard");
        //$myView->assign("firstname", $firstname);
    }
}
