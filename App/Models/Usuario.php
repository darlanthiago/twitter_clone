<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{


    private $id;
    private $nome;
    private $email;
    private $senha;


    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
    }


    //salvar

    public function save(){

        $sql = "INSERT INTO usuario(nome, email, senha) VALUES(:nome, :email, :senha)";

        $sql = $this->db->prepare($sql);

        $sql->bindValue(':nome', $this->__get('nome'));
        $sql->bindValue(':email', $this->__get('email'));
        $sql->bindValue(':senha', md5($this->__get('senha')));

        $sql->execute();

        return $this;
    }

    //validar se um cadastro pode ser feito

    public function validateRegister(){

        $valido = true;

        if (strlen($this->__get('nome')) < 3 || empty($this->__get('nome'))) {
            
            $valido = false;
        }

        if (strlen($this->__get('email')) < 3 || empty($this->__get('email'))) {
            
            $valido = false;
        }

        if (strlen($this->__get('senha')) < 3 || empty($this->__get('senha'))) {
            
            $valido = false;
        }


        return $valido;
        
    }


    //recuperar um usuario por email

    public function recoverByEmail(){

        $sql = "SELECT nome, email FROM usuario WHERE email = :email";

        $sql = $this->db->prepare($sql);

        $sql->bindValue(':email', $this->__get('email'));

        $sql->execute();

        return $sql->fetchAll(\PDO::FETCH_ASSOC);
        
    }

    public function autenticar(){

        $sql = "SELECT id, nome, email FROM usuario WHERE email = :email AND senha = :senha";

        $sql = $this->db->prepare($sql);

        $sql->bindValue(':email', $this->__get('email'));
        
        $sql->bindValue(':senha', md5($this->__get('senha')));

        $sql->execute();

        $usuario = $sql->fetch(\PDO::FETCH_ASSOC);

        if(!empty($usuario['id']) && !empty($usuario['nome'])){

            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);

        }

        return $this;

    }


    public function getAll(){

        $sql = "SELECT 
                    u.id, u.nome, u.email, 
                    (SELECT COUNT(*) 
                    FROM usuarios_seguidores as us 
                    WHERE us.id_usuario = :id_usuario 
                    AND us.id_usuario_seguindo = u.id) as seguindo_sn 
                FROM usuario as u 
                WHERE u.nome 
                LIKE :nome 
                AND u.id != :id_usuario";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':nome', "%".$this->__get('nome')."%");
        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->execute();

        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo){

        $sql = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(:id_usuario, :id_usuario_seguindo)";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $sql->execute();

        return true;

    }

    public function deixarSeguirUsuario($id_usuario_seguindo){
        
        $sql = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
        $sql = $this->db->prepare($sql);

        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

        $sql->execute();

        return true;

    }


    //recuperar o nome do usuario pegar na sessao

    //numero de tweets

    public function getTotalTweets(){

        $sql = "SELECT COUNT(*) as total_tweet FROM tweets WHERE id_usuario = :id_usuario";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->execute();

        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

    //total de seguindo

    public function getTotalSeguindo(){

        $sql = "SELECT COUNT(*) as total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->execute();

        return $sql->fetch(\PDO::FETCH_ASSOC);
    }


    //total seguidores

    public function getTotalSeguidores(){

        $sql = "SELECT COUNT(*) as total_seguidores FROM usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $this->__get('id'));
        $sql->execute();

        return $sql->fetch(\PDO::FETCH_ASSOC);
    }

}