<?php

namespace Bdd;

use PDO;
use App\Debug;

class SQLiteGet {
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
     * get the articles list in the database
     */
    public function getAllArticles() {
        $sql = "SELECT id,title,body FROM articles";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) { 
            $result = $stmt->fetchAll();
        }
        return $result;
    }

    /**
     * get the articles list in the database
     */
    public function getAllUsers() {
        $sql = "SELECT id,login,type,email FROM users";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result) { 
            $result = $stmt->fetchAll();
        }
        return $result;
    }

    /**
     * get the user in the database
     */
    public function getUser($login) {
        $sql = "SELECT * FROM users WHERE login=:login";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ":login"=> $login
        ]);
        if ($result) { 
            $result = $stmt->fetchAll();
        }
        return $result;
    }
}