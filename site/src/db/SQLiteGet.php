<?php

namespace Bdd;

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
        $sql = "SELECT * FROM articles";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        return $result;
    }

    /**
     * get the user in the database
     */
    public function getUser($login) {
        $sql = "SELECT * FROM users WHERE login=:login";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":login"=> $login
        ]);
        $result = $stmt->fetchAll();
        return $result[0];
    }
}