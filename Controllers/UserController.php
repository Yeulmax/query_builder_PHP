<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\User;
use App\Forms\RegisterType;
use App\Managers\UserManager;
use App\Core\Exceptions\NotFoundException;

class UserController extends Controller
{
    public function defaultAction()
    {
        echo "User default";
    }

    public function addAction()
    {
        echo "User add";
    }

    public function getAction($params)
    {
        $userManager = new UserManager();

        $user = $userManager->find($params['id']);


        if(!$user) {
            throw new NotFoundException("User not found");
        }
        
        $users = $userManager->findAll();

        $partialUsers = $userManager->findBy(['firstname' => "Fadyl%"], ['id' => 'desc']);

        $userManager->delete(5);

        echo "get user";
    }

    public function removeAction()
    {
        echo "L'utilisateur va être supprimé";
    }


    public function loginAction()
    {
        $myView = new View("login", "account");
    }

    public function registerAction()
    {

        //$configFormUser = users::getRegisterForm();

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Vérification des champs
            //$errors = Validator::checkForm($configFormUser ,$_POST);
            //Insertion ou erreurs
           // print_r($errors);
        }

        //Insertion d'un user
        /*
        $user = new users();
        $user->setId(1);
        $user->setFirstname("Toto");
        $user->setLastname("Skrzypczyk");
        $user->setEmail("Y.Skrzypczyk@GMAIL.com");
        $user->setPwd("Test1234");
        $user->setStatus(0);
        $user->save();
        */

        $registerType = new RegisterType();

        $this->render("register", "account", [
            "configFormUser" => $registerType
        ]);
    
    }

    public function forgotPwdAction()
    {
        //$myView = new View("forgotPwd", "account");
    }
}
