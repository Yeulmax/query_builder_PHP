<?php

function getRoutes()
{
    return  [
    '/' => [
        'controller' =>  'default',
        'action'=> 'default'
    ],
    '/test/yves' =>
    [
        'controller' =>  'default',
        'action'=> 'default'
    ],
    '/ajout-utilisateur' => [
        'controller'=> "user",
        'action'=> "add"
    ],
    '/get-utilisateur' => [
        'controller'=> "User",
        'action'=> "get"
    ],
    '/utilisateur'=> [
        'controller'=> "user",
        'action'=> "default"
    ],
    "/se-connecter"=>
    ['controller'=> "user",
    'action'=> "login"],

    "/s-inscrire"=>
    ['controller'=> "user",
    'action'=> "register"],

    "/mot-de-passe-oublie"=>
    [ 'controller'=> "user",
    'action'=> "forgotPwd"]
];
}
