<?php

namespace Bdd;

use App\Debug;

class SQLiteSet {
    /**
    * PDO object
    * @var \PDO
    */
    private $pdo;

    /**
    * connect to the SQLite database
    */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
    * set an article in BDD
    */
    public function setArticles($title,$body,$users_id,$name) {
        $sql = 'INSERT INTO articles(title,body,fileName,users_id) VALUES(:title,:body,:fileName,:users_id)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title'=> $title,
            ':body'=> $body,
            ':users_id'=>$users_id,
            ':fileName'=>$name
        ]);
        return $this->pdo->lastInsertId();
    }

        /**
    * set an article in BDD
    */
    public function updateArticles($title,$body,$users_id,$name,$idArticles) {
        $sql = 'UPDATE articles SET title=:title,body=:body,filename=:fileName,users_id=:users_id WHERE id=:id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title'=> $title,
            ':body'=> $body,
            ':users_id'=>$users_id,
            ':fileName'=>$name,
            ':id'=>$idArticles,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * set an commentary in BDD
    */
    public function setComment($title,$body,$users_id,$article_id) {
        $sql = 'INSERT INTO comments(title,body,users_id,articles_id) VALUES(:title,:body,:users_id,:articles_id)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title'=> $title,
            ':body'=> $body,
            ':users_id'=>$users_id,
            ':articles_id'=>$article_id
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * set an user in BDD
    */
    public function setUser($login,$mdp,$email) {
        $sql = 'INSERT INTO users(type,login,email,password) VALUES("User",:login,:email,:mdp)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':login'=> $login,
            ':mdp'=> $mdp,
            ':email'=>$email,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * update an user in BDD
    */
    public function updateUser($idUser,$mdp) {
        $sql = 'UPDATE users SET password=:mdp WHERE id=:id' ;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'=> $idUser,
            ':mdp'=> $mdp,
        ]);
        return $this->pdo->lastInsertId();
    }
}