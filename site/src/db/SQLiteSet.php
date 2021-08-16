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
    public function setArticles($title,$body,$users_id) {
        $sql = 'INSERT INTO articles(title,body,users_id) VALUES(:title,:body,:users_id)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title'=> $title,
            ':body'=> $body,
            ':users_id'=>$users_id,
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
    public function updateUser($id,$mdp) {
        $sql = 'UPDATE users SET password=:mdp WHERE id=:id' ;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'=> $id,
            ':mdp'=> $mdp,
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * set an comment in BDD
    * @var comment string
    */
    public function setComment() {

    }

}