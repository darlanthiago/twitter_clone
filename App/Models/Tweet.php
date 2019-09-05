<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model{


    private $id;
    private $id_usuario;
    private $tweet;
    private $dt_tweet;

    
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

        $sql = "INSERT INTO tweets(id_usuario, tweet) VALUE(:id_usuario, :tweet)";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(":id_usuario", $this->__get('id_usuario'));
        $sql->bindValue(":tweet", $this->__get('tweet'));
        $sql->execute();

        return $this;
    }



    //recuperar

    public function getAll(){

        $sql = "SELECT 
                    t.id, t.id_usuario, u.nome, t.tweet, 
                    DATE_FORMAT(t.dt_tweet, '%d/%m/%Y %H:%i') as dt_tweet 
                FROM 
                    tweets as t
                    LEFT JOIN usuario as u ON(t.id_usuario = u.id)
                WHERE 
                    id_usuario = :id_usuario
                    OR
                    t.id_usuario IN(SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario)
                    
                ORDER BY t.dt_tweet DESC";


        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id_usuario', $this->__get('id_usuario'));
        $sql->execute();

        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function deleteTweet(){

        $sql = "DELETE FROM tweets WHERE id = :id AND id_usuario = :id_usuario";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $this->__get('id'));
        $sql->bindValue(':id_usuario', $this->__get('id_usuario'));
        $sql->execute();

        return true;
    }





}