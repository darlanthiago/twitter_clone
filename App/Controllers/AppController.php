<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{


    public function timeline()
    {

        $this->validaAuth();
        //recuperação dos tweets

        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll();

        // print_r($tweets);

        $this->view->tweets = $tweets;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->render('timeline');
    }

    public function tweet()
    {

        $this->validaAuth();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweet->save();

        header("Location: /timeline?tweet=".urlencode('success'));
    }

    public function validaAuth()
    {
        session_start();
        if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {

            header('Location: /?login=error');
            exit;
        }
    }

    public function quemSeguir(){

        $this->validaAuth();

        $search = (isset($_GET['search']))?$_GET['search']:'';

        $usuarios = array();

        if (!empty($search)) {
            
            $usuario = Container::getModel('Usuario');

            $usuario->__set('nome', $search);

            $usuario->__set('id', $_SESSION['id']);

            $usuarios = $usuario->getAll();

            // print_r($usuarios);
        }

        $this->view->usuarios = $usuarios;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->render('quemSeguir');
    }
    
    public function deleteTweet(){
        $this->validaAuth();

        (isset($_POST['id']) && !empty($_POST['id'])) ? $id = $_POST['id'] : exit;
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $id);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->deleteTweet();
        
        header('Location: /timeline?tweet=success_delete');

    }
    
}
