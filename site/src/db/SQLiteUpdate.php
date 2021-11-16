<?php

namespace Bdd;

class SQLiteUpdate {
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

    public function ValidComment($idComment){
        $sql = "UPDATE comments SET 'valide'='ok' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id"=> $idComment
        ]);
        return 'Compte bien supprimé';
    }
}