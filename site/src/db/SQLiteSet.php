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
    public function setArticles() {

    }

    /**
     * set an user in BDD
     */
    public function setUser($login,$mdp,$email) {
        $sql = 'INSERT INTO users(login,password,email) VALUES(:login,:mdp,:email)';
        $stmt = $this->pdo->prepare($sql);
        //meilleur fonction du monde ^^
        
        $stmt->execute([
            ':login'=> $login,
            ':mdp'=> $mdp,
            ':email'=>$email,
        ]);
        (new Debug)->vardump($this->pdo->errorInfo());
        (new Debug)->vardump($login);
        (new Debug)->vardump($mdp);
        (new Debug)->vardump($email);
        (new Debug)->vardump($this->pdo->lastInsertId());

        return $this->pdo->lastInsertId();
    }

    /**
     * set an comment in BDD
     * @var comment string
     */
    public function setComment() {

    }
}