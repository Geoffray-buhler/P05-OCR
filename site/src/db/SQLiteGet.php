<?php

namespace Bdd;

class SQLiteGet {
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
    public function getUser($login,$password) {
        $sql = "SELECT * FROM users WHERE login=:name,password=:password";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':login'=> $login,
            ':password'=> $password,
        ]);
        return $result;
    }
}