<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action
{

	public function index()
	{

		$this->view->login = isset($_GET['login'])? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse()
	{

		$this->view->erroCadastro = false;
		$this->render('inscreverse');
	}

	public function registrar()
	{

		if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) {

			//receber os dados do form

			$usuario = Container::getModel('Usuario');
			$usuario->__set('nome', $_POST['name']);
			$usuario->__set('email', $_POST['email']);
			$usuario->__set('senha', $_POST['pass']);


			if ($usuario->validateRegister() && count($usuario->recoverByEmail()) == 0) {

				$usuario->save();

				$this->render('cadastro');
			} else {

				$this->view->usuario = array(
					'nome' => $_POST['name'],
					'email' => $_POST['email'],
				);

				$this->view->erroCadastro = true;

				$this->render('inscreverse');
			}
			
		} else {

			$this->inscreverse();
		}

		//sucesso


		//erro
	}
}
