<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action{


    public function autenticar(){

        $usuario = Container::getModel('Usuario');

        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['pass']);

        $retorno = $usuario->autenticar();

        if (!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))) {
            
            session_start();
            $_SESSION['nome'] = $usuario->__get('nome');
            $_SESSION['id'] = $usuario->__get('id');

            header('Location: /timeline');

        } else {

            header('Location: /?login=error');
        }

        // print_r($usuario);
    }

    public function sair(){

        session_start();
        $_SESSION['nome'] = '';
        $_SESSION['id'] = '';

        session_destroy();

        header('Location: /');
    }

}
