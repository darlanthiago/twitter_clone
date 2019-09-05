<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
use App\Controllers\AppController;

class UsuariosSeguidoresController extends Action
{


    public function validaAuth()
    {
        session_start();
        if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {

            header('Location: /?login=error');
            exit;
        }
    }


    public function acao(){

        $this->validaAuth();

        //acao

        $acao = (isset($_GET['acao']) && !empty($_GET['acao']))?$_GET['acao']:'';

        $current_search = $_GET['current_search'];

        $id_usuario_seguindo = (isset($_GET['id_usuario']) && !empty($_GET['id_usuario']))?$_GET['id_usuario']:'';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        if ($acao == 'seguir') {
           
            $usuario->seguirUsuario($id_usuario_seguindo);

        } else if($acao == 'deixarSeguir'){

            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        $current_search = explode('?', $current_search);

        // echo $current_search;
        header("Location: /quem_seguir?".$current_search[1]);

    }

}